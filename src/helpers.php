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

use Workerman\Worker;

define('OPTIONS_SUCCESS', 'success');
define('OPTIONS_ERROR', 'error');

/**
 * 重启
 *
 * @param string $file
 * @return bool
 */
function reload(string $file): bool
{
    Worker::log($file . ' update and reload. ');
    if (extension_loaded('posix') and extension_loaded('pcntl')) {
        return posix_kill(posix_getppid(), SIGUSR1);
    }

    return false;
}

/**
 * 获取本机ip
 *
 * 优先级：本地DNS解析（过滤回环） > 外部服务探测
 *
 * @return string 非回环IP，获取失败返回空字符串
 */
function get_local_ip(): string
{
    // 1. 通过本地DNS解析获取
    $ips = gethostbynamel(gethostname());
    if ($ips !== false) {
        // 过滤回环地址，优先 IPv4
        $ips = array_values(array_filter($ips, function (string $ip): bool {
            return $ip !== '127.0.0.1' && $ip !== '::1';
        }));
        if ($ips) {
            // 多网卡时优先取私有地址（10.x / 172.16-31.x / 192.168.x），更可能是实际通信IP
            $private = array_filter($ips, function (string $ip): bool {
                return (bool) preg_match('/^(10\.|172\.(1[6-9]|2\d|3[01])\.|192\.168\.)/', $ip);
            });
            return $private ? array_pop($private) : array_pop($ips);
        }
    }

    // 2. fallback: 通过外部服务获取公网IP（适用于容器/云主机 where hostname 无法解析出内网IP）
    $ip = trim((string) shell_exec('curl -s --connect-timeout 3 --max-time 5 ifconfig.me 2>/dev/null'));
    if ($ip !== '' && filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
    }

    return '';
}

/**
 * @param string|null $key
 * @param mixed|null $default
 * @return array|mixed|null
 */
function config(?string $key = null, mixed $default = null): mixed
{
    if (Client::$debug) {
        return $default;
    } else {
        return \config($key, $default);
    }
}
