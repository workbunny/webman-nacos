<?php
/**
 * This file is part of workbunny.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    chaz6chez<250220719@qq.com>
 * @copyright chaz6chez<250220719@qq.com>
 * @link      https://github.com/workbunny/webman-nacos
 * @license   https://github.com/workbunny/webman-nacos/blob/main/LICENSE
 */
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