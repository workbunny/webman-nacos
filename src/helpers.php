<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Workerman\Worker;

function reload(string $file)
{
    Worker::log($file . ' update and reload. ');
    if(extension_loaded('posix') and extension_loaded('pcntl')){
        posix_kill(posix_getppid(), SIGUSR1);
    }else{
        Worker::reloadAllWorkers();
    }
}