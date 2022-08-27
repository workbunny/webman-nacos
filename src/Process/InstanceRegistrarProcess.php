<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
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
    protected array $instanceRegistrars;
    /**
     * @var float
     */
    protected float $instanceBeatInterval;
    /**
     * @var int
     */
    protected int $instanceBeatErrorMaxCount;
    /**
     * @var float
     */
    protected float $instanceRegisterRetryInterval;

    /**
     * 注册成功的实例信息
     * @var array
     */
    protected array $successRegistered = [];

    public function __construct()
    {
        parent::__construct();
        if ($instanceRegistrars = config('plugin.workbunny.webman-nacos.app.instance_registrars', [])) {
            foreach ($instanceRegistrars as $instanceRegistrar) {
                $key = md5(serialize($instanceRegistrar));
                if (isset($this->instanceRegistrars[$key])) {
                    // 重复的实例没有必要多次注册
                    continue;
                }
                $this->instanceRegistrars[$key] = $instanceRegistrar;
            }
        }
        $this->instanceBeatInterval = config('plugin.workbunny.webman-nacos.app.instance_beat_interval', 5);
        $this->instanceBeatErrorMaxCount = config('plugin.workbunny.webman-nacos.app.instance_beat_error_max_count', 5);
        $this->instanceRegisterRetryInterval = config('plugin.workbunny.webman-nacos.app.instance_register_retry_interval', 10);
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStart(Worker $worker)
    {
        $worker->count = 1;
        if (!$this->instanceRegistrars) {
            $this->logger()->warning('nacos no instance should register');
            return;
        }

        $this->registerInstances($this->instanceRegistrars);
        $this->maintainFailedRegistrarReRegister();
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStop(Worker $worker)
    {
        try {
            foreach ($this->successRegistered as $instanceRegistrar) {
                // 拆解配置
                list($serviceName, $ip, $port, $option) = $instanceRegistrar;

                $this->client->instance->delete(
                    $serviceName,
                    $option['groupName'] ?? null,
                    $ip,
                    $port,
                    [
                        'namespaceId' => $option['namespaceId'] ?? null,
                        'ephemeral' => $option['ephemeral'] ?? null
                    ]
                );
            }
        } catch (GuzzleException $exception) {
            $this->logger()->error('nacos instance delete error: ' . $exception->getMessage(), ['instance' => $instanceRegistrar, 'exception' => $exception]);
        }
    }

    /**
     * 注册实例
     * @param array $instanceRegistrars
     * @throws GuzzleException
     */
    protected function registerInstances(array $instanceRegistrars)
    {
        $promises = [];
        foreach ($instanceRegistrars as $key => $instanceRegistrar) {
            // 拆解配置
            list($serviceName, $ip, $port, $option) = $instanceRegistrar;
            // 注册
            $promises[$key] = $this->client->instance->registerAsync($ip, $port, $serviceName, $option);
        }
        $responses = Utils::settle($promises)->wait();
        foreach ($responses as $key => $response) {
            $instanceRegistrar = $instanceRegistrars[$key];
            if ($response['state'] === PromiseInterface::REJECTED) {
                /** @var RequestException $reason */
                $reason = $response['reason'];
                $this->logger()->error('nacos register error: ' . $reason->getMessage(), ['instance' => $instanceRegistrar, 'exception' => $reason]);
                continue;
            }
            if ($response['state'] === PromiseInterface::FULFILLED) {
                /** @var ResponseInterface $response */
                $response = $response['value'];
                if ($response->getStatusCode() !== 200) {
                    $this->logger()->error('nacos register error: ' . $response->getBody()->getContents(), ['instance' => $instanceRegistrar]);
                    continue;
                }
                // 注册成功
                $this->logger()->debug('nacos register success', ['instance' => $instanceRegistrar]);
                $this->successRegistered[$key] = $instanceRegistrar;
                // 创建心跳
                $this->createInstanceBeat($instanceRegistrar, $key);
                continue;
            }
            $this->logger()->error('nacos register invalid state: ' . $response['state']);
        }
    }

    /**
     * 维护注册失败的实例重新注册
     */
    protected function maintainFailedRegistrarReRegister()
    {
        Timer::add($this->instanceRegisterRetryInterval, function () {
            $registrars = array_diff_key($this->instanceRegistrars, $this->successRegistered);
            $this->registerInstances($registrars);
        });
    }

    protected array $beatFailedCount = [];
    protected array $beatTimers = [];

    /**
     * 创建心跳
     * @param array $instanceRegistrar
     * @param string $key
     */
    protected function createInstanceBeat(array $instanceRegistrar, string $key)
    {
        $this->beatFailedCount[$key] = 0;
        $this->beatTimers[$key] = Timer::add($this->instanceBeatInterval, function () use ($instanceRegistrar, $key) {
            list($serviceName, $ip, $port, $option) = $instanceRegistrar;
            $this->client->instance->beatAsync(
                $serviceName,
                [
                    'ip' => $ip,
                    'port' => $port,
                    'serviceName' => $serviceName,
                ],
                $option['groupName'] ?? null,
                $option['namespaceId'] ?? null,
                $option['ephemeral'] ?? null
            )->then(function (ResponseInterface $response) use ($instanceRegistrar, $key) {
                if ($response->getStatusCode() !== 200) {
                    $reason = $response->getBody()->getContents();
                    $context = ['instance' => $instanceRegistrar];
                    $this->handleBeatError($key, $reason, $context);
                    return;
                }
                // beat ok
                $this->beatFailedCount[$key] = 0;
                $this->logger()->debug('nacos beat', ['instance' => $instanceRegistrar]);
            }, function (GuzzleException $exception) use ($instanceRegistrar, $key) {
                $reason = $exception->getMessage();
                $context = ['instance' => $instanceRegistrar, 'exception' => $exception];
                $this->handleBeatError($key, $reason, $context);
            })->wait();
        });
    }

    /**
     * 处理 beat 异常
     * @param string $key
     * @param string $reason
     * @param array $context
     */
    protected function handleBeatError(string $key, string $reason, array $context)
    {
        $this->beatFailedCount[$key]++;
        $this->logger()->warning("nacos beat failed [{$this->beatFailedCount[$key]}]: {$reason}", $context);
        if ($this->beatFailedCount[$key] >= $this->instanceBeatErrorMaxCount) {
            // 达到 beat 失败最大次数后界定为实例注册失败
            unset($this->successRegistered[$key]);
            Timer::del($this->beatTimers[$key]);
        }
    }
}