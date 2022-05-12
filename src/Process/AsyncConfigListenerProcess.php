<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use support\Log;
use Workerman\Http\Response;
use Workerman\Timer;
use Workerman\Worker;
use function Workbunny\WebmanNacos\reload;

class AsyncConfigListenerProcess extends AbstractProcess
{
    /** @var int 长轮询间隔 秒 */
    protected int $longPullingInterval;

    /** @var array  */
    protected array $configListeners;

    /** @var array  */
    protected array $timers = [];

    /**
     * ConfigListenerProcess constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->longPullingInterval = config('plugin.workbunny.webman-nacos.app.long_pulling_interval', 30);
        $this->configListeners = config('plugin.workbunny.webman-nacos.app.config_listeners', []);
    }

    /**
     * @param string $dataId
     * @param string $group
     * @param string $tenant
     * @param string $path
     * @throws GuzzleException
     */
    protected function _get(string $dataId, string $group, string $tenant, string $path)
    {
        $res = $this->client->config->get($dataId, $group, $tenant);
        if(file_put_contents($path, $res, LOCK_EX)){
            reload($path);
        }
    }

    /**
     * @description 在进程退出时候，可能会报status 9，是正常现象
     * @param Worker $worker
     * @throws GuzzleException
     */
    public function onWorkerStart(Worker $worker)
    {
        $worker->count = 1;

        if($this->configListeners){
            // 拉取配置项文件
            foreach ($this->configListeners as $listener){
                list($dataId, $group, $tenant, $configPath) = $listener;
                if(!file_exists($configPath)){
                    $this->_get($dataId, $group, $tenant, $configPath);
                }
                $this->timers[$dataId] = Timer::add(
                    $this->longPullingInterval,
                    function () use($dataId, $group, $tenant, $configPath){
                        $this->client->config->listenerAsyncUseEventLoop([
                                'dataId' => $dataId,
                                'group' => $group,
                                'contentMD5' => md5(file_get_contents($configPath)),
                                'tenant' => $tenant
                        ], function (Response $response) use($dataId, $group, $tenant, $configPath){
                            if($response->getStatusCode() === 200){
                                if((string)$response->getBody() !== ''){
                                    $this->_get($dataId, $group, $tenant, $configPath);
                                }
                            }
                        }, function (\Exception $exception){
                            Log::channel('error')->error($exception->getMessage(), $exception->getTrace());
                        });
                });
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStop(Worker $worker){
        foreach ($this->timers as $timer){
            Timer::del($timer);
        }
    }
}