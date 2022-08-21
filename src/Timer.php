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

use Workerman\Timer as WorkermanTimer;

/**
 * 定时器
 *
 * @desc 对workerman/timer的封装
 * 1.延迟单此执行
 * 2.立即单次执行
 * 3.延迟循环执行
 *      - 延迟与循环时间不同
 *      - 延迟与循环间隔相同
 * 4.立即循环执行
 * @author chaz6chez
 */
final class Timer {

    /** @var array[] 子定时器 */
    protected static array $_timers = [];

    /**
     * 新增定时器
     * @param float $delay
     * @param float $repeat
     * @param callable $callback
     * @param ...$args
     * @return int|bool
     */
    public static function add(float $delay, float $repeat, callable $callback, ... $args)
    {
        switch (true){
            # 立即循环
            case ($delay === 0.0 and $repeat !== 0.0):
                $callback(...$args);
                return WorkermanTimer::add($repeat, $callback, $args);

            # 延迟执行一次
            case ($delay !== 0.0 and $repeat === 0.0):
                return WorkermanTimer::add($delay, $callback, $args, false);

            # 延迟循环执行，延迟与重复相同
            case ($delay !== 0.0 and $repeat !== 0.0 and $repeat === $delay):
                return WorkermanTimer::add($delay, $callback, $args);

            # 延迟循环执行，延迟与重复不同
            case ($delay !== 0.0 and $repeat !== 0.0 and $repeat !== $delay):
                return $id = WorkermanTimer::add($delay, function(...$args) use(&$id, $repeat, $callback){
                    $callback(...$args);
                    self::$_timers[$id] = WorkermanTimer::add($repeat, $callback, $args);
                }, $args, false);

            # 立即执行
            default:
                $callback(...$args);
                return 0;
        }
    }

    /**
     * 移除定时器
     * @param int $id
     * @return void
     */
    public static function del(int $id): void
    {
        if(
            $id !== 0 and
            isset(self::$_timers[$id]) and
            is_int($timerId = self::$_timers[$id])
        ){
            unset(self::$_timers[$id]);
            WorkermanTimer::del($timerId);
        }
    }

    /**
     * @return void
     */
    public static function delAll(): void
    {
        self::$_timers = [];
        WorkermanTimer::delAll();
    }
}