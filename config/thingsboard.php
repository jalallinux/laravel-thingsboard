<?php

return [

    'container' => [
        'namespace' => 'thingsboard',
        'prefix' => [
            'entity' => 'entity',
        ],
    ],

    'rest' => [
        'base_uri' => env('THINGSBOARD_BASE_URI', 'localhost:9090'),
        'authorization' => [
            'header_key' => 'X-Authorization',
            'token_type' => 'Bearer',
        ],
        'users' => [
            [
                'role' => \JalalLinuX\Thingsboard\Enums\EnumAuthority::SYS_ADMIN(),
                'mail' => env('THINGSBOARD_ADMIN_MAIL', 'sysadmin@thingsboard.org'),
                'pass' => env('THINGSBOARD_ADMIN_PASS', 'sysadmin'),
            ],
            [
                'role' => \JalalLinuX\Thingsboard\Enums\EnumAuthority::TENANT_ADMIN(),
                'mail' => env('THINGSBOARD_TENANT_MAIL', 'tenant@thingsboard.org'),
                'pass' => env('THINGSBOARD_TENANT_PASS', 'tenant'),
            ],
            [
                'role' => \JalalLinuX\Thingsboard\Enums\EnumAuthority::CUSTOMER_USER(),
                'mail' => env('THINGSBOARD_CUSTOMER_MAIL', 'customer@thingsboard.org'),
                'pass' => env('THINGSBOARD_CUSTOMER_PASS', 'customer'),
            ],
        ],
        'exception' => [
            /**
             * True => Boolean methods return exception
             * False => Boolean methods return false
             */
            'throw_bool_methods' => boolval(env('THINGSBOARD_EXCEPTION_THROW_BOOL_METHOD', true)),
        ],
    ],

    'cache' => [
        'prefix' => '_thingsboard_',
        'driver' => env('THINGSBOARD_CACHE_DRIVER', 'redis'),
    ],

    'countries' => json_decode(file_get_contents(__DIR__ . "/countries.json"), true)
];
