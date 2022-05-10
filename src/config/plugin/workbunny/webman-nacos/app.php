<?php
return [
    'enable' => true,

    'host' => '127.0.0.1',
    'port' => 8848,
    'username' => '',
    'password' => '',

    /** 长轮询等待时长 毫秒 @desc 当长轮询间隔不存在时，该项作为默认值使用，其余时间则不生效 */
    'long_pulling_timeout'  => 30000,

    /** 长轮询间隔 秒 @desc 组件包主要使用该项作为监听器间隔，使用{该值 * 1000}作为长轮询等待时长 */
    'long_pulling_interval'  => 30,

    /** 配置文件监听器 @see \Workbunny\WebmanNacos\Process\ConfigListenerProcess */
    'config_listeners' => [
        [
            /** dataId @desc 该值最好与配置文件名相同 */
            'config.yaml',
            /** groupName */
            'DEFAULT_GROUP',
            /** namespaceId */
            '',
            /** filePath @desc 配置文件本地保存的地址 */
            base_path() . '/config.yaml',
            /** ignore @desc true:不启动监听 false:启动监听 */
            true
        ],
    ],

    /** 实例注册器 @see \Workbunny\WebmanNacos\Process\InstanceRegistrarProcess */
    'instance_registrars' => [
        'main' => [
            /** serviceName */
            config('app.name'),
            /** ip */
            '',
            /** port */
            8787,
            /** optional @see \Workbunny\WebmanNacos\Provider\InstanceProvider::registerAsync() */
            [
                'groupName' => 'DEFAULT_GROUP',
                'namespaceId' => '',
                'enabled' => true,
                'ephemeral' => false
            ],
            /** $ignore @desc true:不启动注册 false:启动注册 */
            true
        ],
    ]
];