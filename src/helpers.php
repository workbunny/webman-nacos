<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Workerman\Worker;

define('OPTIONS_SUCCESS','success');
define('OPTIONS_ERROR','error');

function reload(string $file)
{
    Worker::log($file . ' update and reload. ');
    if(extension_loaded('posix') and extension_loaded('pcntl')){
        posix_kill(posix_getppid(), SIGUSR1);
    }else{
        Worker::reloadAllWorkers();
    }
}