<?php
declare(strict_types=1);

return [
    'instance-registrar' => [
        'handler'  => \support\package\nacos\process\InstanceRegistrarProcess::class
    ],
    'config-listener' => [
        'handler'  => \support\package\nacos\process\ConfigListenerProcess::class
    ]
];
