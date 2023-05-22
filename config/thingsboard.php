<?php

return [

    'container' => [
        'namespace' => 'thingsboard',
        'prefix' => [
            'entity' => 'entity',
        ],
    ],

    'rest' => [
        'base_uri' => env('THINGSBOARD_BASE_URI', 'localhost:8080'),
        'authorization' => [
            'header_key' => 'Authorization',
            'token_type' => 'Bearer',
        ],
        'admin' => [
            'mail' => env('THINGSBOARD_ADMIN_MAIL', 'sysadmin@thingsboard.org'),
            'pass' => env('THINGSBOARD_ADMIN_PASS', 'sysadmin'),
        ],
    ],

    'cache' => [
        'prefix' => '_thingsboard_',
        'driver' => env('THINGSBOARD_CACHE_DRIVER', 'redis'),
    ],
];
