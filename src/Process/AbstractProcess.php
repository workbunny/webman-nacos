<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use Workbunny\WebmanNacos\Client as NacosClient;
use Workbunny\WebmanNacos\Traits\Logger;
use Workerman\Worker;

abstract class AbstractProcess
{
    use Logger;

    /** @var NacosClient  */
    protected NacosClient $client;

    /** @var int 进程重试间隔 */
    protected int $retry_interval;

    public function __construct()
    {
        $this->client = new NacosClient();
        $this->retry_interval = config('plugin.workbunny.webman-nacos.app.process_retry_interval', 5);
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