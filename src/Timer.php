<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos;

use Workerman\Timer as WorkermanTimer;

final class Timer {

    /** @var int id */
    protected static int $_id = 1;

    /** @var array[] ids */
    protected static array $_timers = [];

    /**
     * 新增定时器
     * @param float $delay
     * @param float $repeat
     * @param callable $callback
     * @param ...$args
     * @return int
     */
    public static function add(float $delay, float $repeat, callable $callback, ... $args): int
    {
        switch (true){
            # 立即循环
            case ($delay === 0.0 and $repeat !== 0.0):
                $callback(...$args);
                self::$_timers[self::$_id] = [
                    WorkermanTimer::add($repeat, $callback, $args, true)
                ];
                return self::$_id ++;

            # 延迟执行一次
            case ($delay !== 0.0 and $repeat === 0.0):
                self::$_timers[self::$_id] = [
                    WorkermanTimer::add($delay, $callback, $args, false)
                ];
                return self::$_id ++;

            # 延迟循环执行，延迟与重复相同
            case ($delay !== 0.0 and $repeat !== 0.0 and $repeat === $delay):
                self::$_timers[self::$_id] = [
                    WorkermanTimer::add($delay, $callback, $args, true)
                ];
                return self::$_id ++;

            # 延迟循环执行，延迟与重复不同
            case ($delay !== 0.0 and $repeat !== 0.0 and $repeat !== $delay):
                self::$_timers[self::$_id] = [
                    WorkermanTimer::add($delay, $callback, $args, false),
                    WorkermanTimer::add($repeat + $delay, $callback, $args, true)
                ];
                return self::$_id ++;

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
        if($id !== 0 and isset(self::$_timers[$id])){
            foreach (self::$_timers as $timerId){
                if(is_int($timerId)){
                    WorkermanTimer::del($timerId);
                }
            }
        }
    }
}