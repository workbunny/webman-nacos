<?php

declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use function Workbunny\WebmanNacos\get_local_ip;
use Workerman\Timer;
use Workerman\Worker;

/**
 * nacos注册实例类
 * @author liuchangchen
 */
class InstanceRegistrarProcess extends AbstractProcess
{
    /**
     * @var array
     */
    protected array $instanceRegistrars = [];

    /**
     * @var array
     */
    protected array $heartbeatTimers = [];

    /**
     * 每个实例的连续心跳失败计数
     * @var array<string,int>
     */
    protected array $heartbeatFailCount = [];

    /**
     * @var float
     */
    protected float $heartbeat;

    /**
     * 允许的最大连续心跳失败次数，超过才重启进程
     * @var int
     */
    protected int $heartbeatFailMax;

    public function __construct()
    {
        parent::__construct();
        $this->heartbeat = (float) config('plugin.workbunny.webman-nacos.app.instance_heartbeat', 5.0);
        $this->heartbeatFailMax = (int) config('plugin.workbunny.webman-nacos.app.instance_heartbeat_fail_max', 3);
    }

    /**
     * 心跳
     * @param string $name
     * @return void
     */
    protected function _heartbeat(string $name): void
    {
        if (!isset($this->instanceRegistrars[$name])) {
            return;
        }
        $instanceRegistrar = $this->instanceRegistrars[$name];
        $serviceName = $instanceRegistrar['service_name'];
        $ip = $instanceRegistrar['pod_ip'] ?: get_local_ip();
        $port = $instanceRegistrar['pod_port'];
        $option = $instanceRegistrar['options'] ?? [];

        // 关键修复：Nacos OpenAPI 的 ephemeral 默认值为 true（临时实例）
        // 当用户未显式指定时必须按 true 处理，否则会导致临时实例不发心跳而被 Nacos 摘除
        if (array_key_exists('ephemeral', $option)) {
            $ephemeral = is_string($option['ephemeral'])
                ? filter_var($option['ephemeral'], FILTER_VALIDATE_BOOLEAN)
                : (bool) $option['ephemeral'];
        } else {
            $ephemeral = true;
        }
        $option['ephemeral'] = $ephemeral;

        // 仅对临时实例进行心跳（永久实例由服务端健康检查维持）
        if (!$ephemeral) {
            return;
        }

        $this->heartbeatFailCount[$name] = 0;
        $this->heartbeatTimers[$name] = Timer::add($this->heartbeat, function () use ($name, $serviceName, $ip, $port, $option) {
            if ($this->is_stopping) {
                return;
            }
            // beat JSON 只保留 Nacos 识别的标准字段，避免非标字段污染
            $beatData = array_filter([
                'ip'          => $ip,
                'port'        => $port,
                'serviceName' => $serviceName,
                'cluster'     => $option['clusterName'] ?? ($option['cluster'] ?? null),
                'weight'      => $option['weight'] ?? null,
                'metadata'    => $option['metadata'] ?? null,
                'scheduled'   => $option['scheduled'] ?? null,
            ], fn ($value) => $value !== null);

            try {
                $result = $this->client->instance->beat(
                    $serviceName,
                    $beatData,
                    $option['groupName'] ?? null,
                    $option['namespaceId'] ?? null,
                    $option['ephemeral'] ?? null,
                    false,
                    $this->heartbeat
                );
                if ($result === false) {
                    $this->_onHeartbeatFail(
                        $name,
                        "Nacos instance heartbeat failed: [0] {$this->client->instance->getMessage()}.",
                        []
                    );

                    return;
                }
                // 心跳成功，重置失败计数
                $this->heartbeatFailCount[$name] = 0;
            } catch (\Throwable $exception) {
                $this->_onHeartbeatFail(
                    $name,
                    "Nacos instance heartbeat failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                    $exception->getTrace()
                );
            }
        });
    }

    /**
     * 心跳失败处理：累计 N 次失败才重启进程，避免偶发抖动导致雪崩
     * @param string $name
     * @param string $message
     * @param array $trace
     * @return void
     */
    protected function _onHeartbeatFail(string $name, string $message, array $trace = []): void
    {
        $this->heartbeatFailCount[$name] = ($this->heartbeatFailCount[$name] ?? 0) + 1;
        $count = $this->heartbeatFailCount[$name];
        $this->logger()->error($message, [
            'name'      => $name,
            'fail_count'=> $count,
            'max'       => $this->heartbeatFailMax,
            'trace'     => $trace,
        ]);
        if ($count >= $this->heartbeatFailMax) {
            $this->_stop($this->retry_interval);
        }
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStart(Worker $worker)
    {
        $worker->count = 1;
        try {
            if ($instanceRegistrars = config('plugin.workbunny.webman-nacos.registrars', [])) {
                $promises = [];
                foreach ($instanceRegistrars as $name => $instanceRegistrar) {
                    // 拆解配置
                    $serviceName = $instanceRegistrar['service_name'];
                    $ip = $instanceRegistrar['pod_ip'] ?: get_local_ip();
                    $port = $instanceRegistrar['pod_port'];
                    $option = $instanceRegistrar['options'] ?? [];
                    // 注册
                    $promises[] = $this->client->instance->registerAsync($ip, $port, $serviceName, $option)
                        ->then(function (ResponseInterface $response) use ($instanceRegistrar, $name) {
                            if ($response->getStatusCode() === 200) {
                                $this->instanceRegistrars[$name] = $instanceRegistrar;
                                $this->_heartbeat($name);

                                return;
                            }
                            $this->logger()->error(
                                "Naocs instance register failed: [0] {$this->client->instance->getMessage()}.",
                                ['name' => $name, 'trace' => []]
                            );
                            $this->_stop($this->retry_interval);
                        }, function (\Exception $exception) use ($instanceRegistrar, $name) {
                            $this->logger()->error(
                                "Nacos instance register failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                                ['name' => $name, 'trace' => $exception->getTrace()]
                            );
                            $this->_stop($this->retry_interval);
                        });
                }
                Utils::settle($promises)->wait();
            }
        } catch (GuzzleException $exception) {
            $this->logger()->error(
                "Nacos instance register failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                ['name' => '#base', 'trace' => $exception->getTrace()]
            );

            $this->_stop($this->retry_interval);
        }
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStop(Worker $worker)
    {
        try {
            foreach ($this->instanceRegistrars as $name => $instanceRegistrar) {
                // 移除心跳
                if (isset($this->heartbeatTimers[$name])) {
                    Timer::del($this->heartbeatTimers[$name]);
                }
                // 拆解配置
                $serviceName = $instanceRegistrar['service_name'];
                $ip = $instanceRegistrar['pod_ip'] ?: get_local_ip();
                $port = $instanceRegistrar['pod_port'];
                $option = $instanceRegistrar['options'] ?? [];
                // 注销实例（groupName 未配置时使用 Nacos 默认的 DEFAULT_GROUP，避免 null 触发 TypeError）
                if (!$this->client->instance->delete(
                    $serviceName,
                    (string) ($option['groupName'] ?? 'DEFAULT_GROUP'),
                    $ip,
                    $port,
                    [
                        'namespaceId' => $option['namespaceId'] ?? null,
                        'ephemeral'   => $option['ephemeral'] ?? null,
                        'clusterName' => $option['clusterName'] ?? null,
                    ]
                )) {
                    $this->logger()->error(
                        "Naocs instance delete failed: [0] {$this->client->instance->getMessage()}.",
                        ['name' => $name, 'trace' => []]
                    );
                }
            }
        } catch (\Throwable $exception) {
            $this->logger()->error(
                "Nacos instance delete failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                ['name' => '#base', 'trace' => $exception->getTrace()]
            );
        }
    }
}
