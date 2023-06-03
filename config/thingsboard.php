<?php

return [

    'container' => [
        'namespace' => 'thingsboard',
        'prefix' => [
            'entity' => 'entity',
        ],
    ],

    'default' => [
        'tenant_id' => '13814000-1dd2-11b2-8080-808080808080',
        'customer_id' => '13814000-1dd2-11b2-8080-808080808080',
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

        'rpc' => [
            'default_attributes' => [
                /**
                 * timeout - optional, value of the processing timeout in milliseconds.
                 * The default value is 10000 (10 seconds). The minimum value is 5000 (5 seconds).
                 */
                'timeout' => 10000,

                /**
                 * expirationTime - optional, value of the epoch time (in milliseconds, UTC timezone).
                 * Overrides timeout if present.
                 */
                'expirationTime' => now()->addMinute()->getPreciseTimestamp(3),

                /**
                 * persistent - optional, indicates persistent RPC. The default value is "false".
                 */
                'persistent' => false,

                /**
                 * retries - optional, defines how many times persistent RPC will be re-sent in case of failures on the network and/or device side.
                 */
                'retries' => 1,

                /**
                 * additionalInfo - optional, defines metadata for the persistent RPC that will be added to the persistent RPC events.
                 */
                'additionalInfo' => [],
            ],
        ],
    ],

    'cache' => [
        'prefix' => '_thingsboard_',
        'driver' => env('THINGSBOARD_CACHE_DRIVER', 'redis'),
    ],

    'countries' => json_decode(file_get_contents(__DIR__.'/countries.json'), true),

    'temp_path' => storage_path('app/public/'.uniqid()),
];
