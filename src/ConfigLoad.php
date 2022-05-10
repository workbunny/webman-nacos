<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Webman\Bootstrap;
use Workerman\Worker;

class ConfigLoad implements Bootstrap
{
    public static function start($worker)
    {
        if($worker instanceof Worker){
            $worker->onWorkerStop = $worker->onWorkerReload = function (){
                @unlink(base_path() . '/config.reload.lock');
            };
        }
    }
}
