<?php

declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;

use function Workbunny\WebmanNacos\reload;

use Workbunny\WebmanNacos\Timer;
use Workerman\Worker;

/**
 * 单Timer阻塞监听器
 *
 * @desc 该监听器是独立进程创建了单一的Timer进行定时长轮询阻塞请求，
 * 多个配置的长轮询请求会交给Guzzle的异步客户端进行并发请求；
 *      整体逻辑在Timer的执行周期内是阻塞的，这里Timer的间隔时间与
 * 长轮询请求时长保持一直的原因也在于避免影响下一个Timer的执行周期；
 *      该监听器有一个缺点，就是在workerman的监听命令中，该进程会
 * 始终保持BUSY状态。
 *
 * @author chaz6chez
 */
class ConfigListenerProcess extends AbstractProcess
{
    /** @var int 长轮询间隔 秒 */
    protected int $longPullingInterval;

    /** @var array */
    protected array $configListeners;

    /**
     * ConfigListenerProcess constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->longPullingInterval = config('plugin.workbunny.webman-nacos.app.long_pulling_interval', 30);
        $this->configListeners = config('plugin.workbunny.webman-nacos.listeners', []);
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
        if (file_put_contents($path, $res, LOCK_EX)) {
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

        if ($this->configListeners) {
            // 拉取配置项文件
            foreach ($this->configListeners as $listener) {
                $dataId = $listener['data_id'];
                $group = $listener['group_name'];
                $tenant = $listener['namespace_id'];
                $configPath = $listener['config_path'];
                if (!file_exists($configPath)) {
                    $this->_get($dataId, $group, $tenant, $configPath);
                }
            }
            // 创建定时监听
            Timer::add(0.0, (float) $this->longPullingInterval, function () {
                $promises = [];
                foreach ($this->configListeners as $listener) {
                    list($dataId, $group, $tenant, $configPath) = $listener;
                    if (file_exists($configPath)) {
                        $promises[] = $this->client->config->listenerAsync(
                            $dataId,
                            $group,
                            md5(file_get_contents($configPath)),
                            $tenant,
                            $this->longPullingInterval * 1000
                        )->then(function (ResponseInterface $response) use ($dataId, $group, $tenant, $configPath) {
                            if ($response->getStatusCode() === 200) {
                                if ($response->getBody()->getContents() !== '') {
                                    $this->_get($dataId, $group, $tenant, $configPath);
                                }

                                return;
                            }
                            $this->logger()->error(
                                "Nacos listener failed: [0] {$this->client->config->getMessage()}.",
                                ['dataId' => $dataId, 'trace' => []]
                            );
                            $this->_stop($this->retry_interval);
                        }, function (GuzzleException $exception) use ($dataId) {
                            $this->logger()->error(
                                "Nacos listener failed: [{$exception->getCode()}] {$exception->getMessage()}.",
                                ['dataId' => $dataId, 'trace' => $exception->getTrace()]
                            );
                            $this->_stop($this->retry_interval);
                        });
                    }
                }
                if ($promises) {
                    Utils::settle($promises)->wait();
                }
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStop(Worker $worker)
    {
    }
}
