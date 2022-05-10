<?php
declare(strict_types=1);

return [
    'instance-registrar' => [
        'handler'  => \Workbunny\WebmanNacos\Process\InstanceRegistrarProcess::class
    ],
    'config-listener' => [
        'handler'  => \Workbunny\WebmanNacos\Process\ConfigListenerProcess::class
    ]
];
