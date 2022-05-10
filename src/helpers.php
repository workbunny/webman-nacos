<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Webman\Config;

function config($key = null, $default = null)
{
    if(!file_exists(base_path() . '/config.reload.lock')){
        file_put_contents(base_path() . '/config.reload.lock', 1, LOCK_EX);
        Config::reload(config_path(), ['route', 'container']);
    }
    return Config::get($key, $default);
}