<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Process;

use Workbunny\WebmanNacos\Client as NacosClient;
use Workerman\Worker;

abstract class AbstractProcess
{
    /** @var NacosClient  */
    protected NacosClient $client;

    public function __construct()
    {
        $this->client = new NacosClient();
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