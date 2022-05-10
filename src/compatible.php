<?php
declare(strict_types=1);

use Webman\Config;

if(
    function_exists('runkit7_function_remove') and
    PHP_MAJOR_VERSION === 7
){
    if(function_exists('config')){
        runkit7_function_remove('config');
    }
    function config($key = null, $default = null)
    {
        if(!file_exists(base_path() . '/config.reload.lock')){
            file_put_contents(base_path() . '/config.reload.lock', 1, LOCK_EX);
            Config::reload(config_path(), ['route', 'container']);
        }
        return Config::get($key, $default);
    }
}
else{
    require 'helpers.php';
}