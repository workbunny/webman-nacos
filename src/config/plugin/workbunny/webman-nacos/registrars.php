<?php

declare(strict_types=1);

return [
    'main' => [
        'service_name' => '{your_service_name}',
        'pod_ip'       => '{your_pod_ip}',
        'pod_port'     => '{your_pod_port}',

        /** optional @see \Workbunny\WebmanNacos\Provider\InstanceProvider::registerAsync() */
        'options'      => [
            'groupName'   => 'DEFAULT_GROUP',
            'namespaceId' => '{your_namespace_id}',
            'enabled'     => 'true',
            'ephemeral'   => 'false',
        ],
    ],
];
