<?php

declare(strict_types=1);

return [
    'instance-registrar' => [
        'handler'   => \Workbunny\WebmanNacos\Process\InstanceRegistrarProcess::class,
        'eventLoop' => \Workerman\Events\Event::class,
    ],
    'config-listener' => [
        'handler'   => \Workbunny\WebmanNacos\Process\AsyncConfigListenerProcess::class,
        'eventLoop' => \Workerman\Events\Event::class,
    ],
];
