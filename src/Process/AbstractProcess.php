<?php

declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use Workbunny\WebmanNacos\Client as NacosClient;
use Workbunny\WebmanNacos\Traits\Logger;
use Workerman\Timer;
use Workerman\Worker;

abstract class AbstractProcess
{
    use Logger;

    /** @var NacosClient */
    protected NacosClient $client;

    /** @var int 进程重试间隔 */
    protected int $retry_interval;

    /**
     * @var bool
     */
    protected bool $is_stopping;

    public function __construct(string $channel = 'default')
    {
        $this->client = NacosClient::channel($channel);
        $this->retry_interval = (int) config('plugin.workbunny.webman-nacos.app.process_retry_interval', 5);
        $this->is_stopping = false;
    }

    /**
     * 停止当前进程
     * @param int|float $sleep
     * @return void
     */
    protected function _stop(int|float $sleep = 0): void
    {
        if ($this->is_stopping) {
            return;
        }

        $this->is_stopping = true;
        if ($sleep > 0) {
            Timer::add($sleep, function () {
                Worker::stopAll();
            });

            return;
        }
        Worker::stopAll();
    }

    /**
     * @param Worker $worker
     * @return mixed
     */
    abstract public function onWorkerStart(Worker $worker);

    /**
     * @param Worker $worker
     * @return mixed
     */
    abstract public function onWorkerStop(Worker $worker);
}
