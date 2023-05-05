<?php
/**
 * This file is part of workbunny.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    chaz6chez<chaz6chez1993@outlook.com>
 * @copyright chaz6chez<chaz6chez1993@outlook.com>
 * @link      https://github.com/workbunny/webman-nacos
 * @license   https://github.com/workbunny/webman-nacos/blob/main/LICENSE
 */
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Webman\Config;
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

/**
 * @param string|null $key
 * @param mixed|null $default
 * @return array|mixed|null
 */
function config(string $key = null, $default = null)
{
    if(Client::$debug) {
        return $default;
    }else{
        return \config($key, $default);
    }
}