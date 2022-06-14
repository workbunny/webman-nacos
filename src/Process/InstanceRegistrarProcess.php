<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use support\Log;
use Workerman\Timer;
use Workerman\Worker;
use GuzzleHttp\Exception\GuzzleException;

/**
 * nacos注册实例类
 * @author liuchangchen
 */
class InstanceRegistrarProcess extends AbstractProcess
{

    /** @var array  */
    protected array $instanceRegistrars;

    public function __construct()
    {
        parent::__construct();
        $this->instanceRegistrars = config('plugin.workbunny.webman-nacos.app.instance_registrars');
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStart(Worker $worker)
    {
        $worker->count = 1;
        if($this->instanceRegistrars){
            $promises = [];
            foreach ($this->instanceRegistrars as $instanceRegistrar){
                // 拆解配置
                list($serviceName, $ip, $port, $option) = $instanceRegistrar;
                // 注册
                $promises[] = $this->client->instance->registerAsync($ip, $port, $serviceName, $option)
                    ->then(function (ResponseInterface $response) use($instanceRegistrar) {

                        if($response->getStatusCode() === 200){
                            // 创建心跳
                            Timer::add(5, function () use ($instanceRegistrar){
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
                                )->then(function (ResponseInterface $response) {
                                    if($response->getStatusCode() !== 200){
                                        Log::debug('nacos beat failed', [$response->getBody()->getContents()]);
                                    }
                                }, function (GuzzleException $exception){
                                    Log::channel('error')->error($exception->getMessage(),$exception->getTrace());
                                })->wait();
                            });
                        }
                    }, function (GuzzleException $exception){
                        Log::channel('error')->error($exception->getMessage(),$exception->getTrace());
                    });
            }
            if($promises){
                Utils::settle($promises)->wait();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStop(Worker $worker)
    {
        if($this->instanceRegistrars){
            try {
                foreach ($this->instanceRegistrars as $instanceRegistrar) {
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
            }catch (GuzzleException $exception){
                Log::channel('error')->error($exception->getMessage(),$exception->getTrace());
            }
        }

    }
}