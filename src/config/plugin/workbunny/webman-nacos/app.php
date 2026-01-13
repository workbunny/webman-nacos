<?php

declare(strict_types=1);

return [
    'enable' => true,
    /* 长轮询等待时长 毫秒 @desc 当长轮询间隔不存在时，该项作为默认值使用，其余时间则不生效 */
    'long_pulling_timeout'  => 30000,
    /* 长轮询间隔 秒 @desc 组件包主要使用该项作为监听器间隔，使用{该值 * 1000}作为长轮询等待时长 */
    'long_pulling_interval'  => 30,
    /* float 实例心跳间隔 秒 */
    'instance_heartbeat' => 5.0,
    /* int 进程重试间隔 秒 */
    'process_retry_interval' => 5,
    /* 日志 channel，为 null 时使用默认通道 */
    'log_channel' => null, // 'error',
    /*
     * 配置文件监听器
     * @desc 可在config/plugin/workbunny/webman-nacos/process.php中进行修改以下两种监听器
     * @see \Workbunny\WebmanNacos\Process\ConfigListenerProcess 单Timer同步监听器
     * @see \Workbunny\WebmanNacos\Process\AsyncConfigListenerProcess 多Timer异步监听器
     */
    'config_listeners' => [

        # 以下可以新增多个数组
    ],
];
