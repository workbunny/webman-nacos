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
    protected array $instanceRegistrars = [];

    /**
     * @var array
     */
    protected array $heartbeatTimers = [];

    /**
     * @var float
     */
    protected float $heartbeat;

    public function __construct()
    {
        parent::__construct();
        $this->heartbeat = (float)config('plugin.workbunny.webman-nacos.app.instance_heartbeat', 5.0);
    }

    /**
     * 心跳
     * @param string $name
     * @return void
     */
    protected function _heartbeat(string $name): void
    {
        if(isset($this->instanceRegistrars[$name])){
            $this->heartbeatTimers[$name] = Timer::add($this->heartbeat, function () use ($name) {
                list($serviceName, $ip, $port, $option) = $this->instanceRegistrars[$name];
                try {
                    if(!$this->client->instance->beat(
                        $serviceName,
                        array_filter([
                            'ip' => $ip,
                            'port' => $port,
                            'serviceName' => $serviceName,
                            'weight' => $option['weight'] ?? null,
                            'cluster' => $option['cluster'] ?? null,
                        ], fn($value) => $value !== null),
                        $option['groupName'] ?? null,
                        $option['namespaceId'] ?? null,
                        $option['ephemeral'] ?? null
                    )){
                        $this->logger()->error(
                            "Nacos instance heartbeat failed: [0] {$this->client->instance->getMessage()}.",
                            ['name' => $name, 'trace' => []]
                        );
                        sleep($this->retry_interval);
                        Worker::stopAll(0);
                    }
                }catch (GuzzleException $exception){
                    $this->logger()->error(
                        "Nacos instance heartbeat failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                        ['name' => $name, 'trace' => $exception->getTrace()]
                    );
                    sleep($this->retry_interval);
                    Worker::stopAll(0);
                }
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStart(Worker $worker)
    {
        $worker->count = 1;
        try {
            if($instanceRegistrars = config('plugin.workbunny.webman-nacos.app.instance_registrars', [])){
                $promises = [];
                foreach ($instanceRegistrars as $name => $instanceRegistrar){
                    // 拆解配置
                    list($serviceName, $ip, $port, $option) = $instanceRegistrar;
                    // 注册
                    $promises[] = $this->client->instance->registerAsync($ip, $port, $serviceName, $option)
                        ->then(function (ResponseInterface $response) use ($instanceRegistrar, $name) {
                            if($response->getStatusCode() === 200){
                                $this->instanceRegistrars[$name] = $instanceRegistrar;
                                $this->_heartbeat($name);
                            }else{
                                $this->logger()->error(
                                    "Naocs instance register failed: [0] {$this->client->instance->getMessage()}.",
                                    ['name' => $name, 'trace' => []]
                                );

                                sleep($this->retry_interval);
                                Worker::stopAll(0);
                            }
                        }, function (\Exception $exception) use ($instanceRegistrar, $name) {
                            $this->logger()->error(
                                "Nacos instance register failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                                ['name' => $name, 'trace' => $exception->getTrace()]
                            );

                            sleep($this->retry_interval);
                            Worker::stopAll(0);
                        });
                }
                Utils::settle($promises)->wait();
            }

        }catch (GuzzleException $exception) {
            $this->logger()->error(
                "Nacos instance register failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                ['name' => '#base', 'trace' => $exception->getTrace()]
            );

            sleep($this->retry_interval);
            Worker::stopAll(0);
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
                if(isset($this->heartbeatTimers[$name])){
                    Timer::del($this->heartbeatTimers[$name]);
                }
                // 拆解配置
                list($serviceName, $ip, $port, $option) = $instanceRegistrar;
                if(!$this->client->instance->delete(
                    $serviceName,
                    $option['groupName'] ?? null,
                    $ip,
                    $port,
                    [
                        'namespaceId' => $option['namespaceId'] ?? null,
                        'ephemeral' => $option['ephemeral'] ?? null
                    ]
                )){
                    $this->logger()->error(
                        "Naocs instance delete failed: [0] {$this->client->instance->getMessage()}.",
                        ['name' => $name, 'trace' => []]
                    );
                }
            }
        } catch (GuzzleException $exception) {
            $this->logger()->error(
                "Nacos instance delete failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                ['name' => '#base', 'trace' => $exception->getTrace()]
            );
        }
    }
}