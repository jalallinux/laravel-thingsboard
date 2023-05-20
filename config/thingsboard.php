<?php

return [

    'api' => [
        'default' => [
            'base_uri' => env('THINGSBOARD_BASE_URI', 'localhost:8080'),
            'admin' => [
                'mail' => env('THINGSBOARD_ADMIN_MAIL', 'sysadmin@thingsboard.org'),
                'pass' => env('THINGSBOARD_ADMIN_PASS', 'sysadmin'),
                //                'user_id' => env('THINGSBOARD_ADMIN_USER_ID', 'cf8f9240-ed9b-11ed-9c52-f37143f58f87'),
                //                'tenant_id' => env('THINGSBOARD_ADMIN_TENANT_ID', '13814000-1dd2-11b2-8080-808080808080'),
                //                'customer_id' => env('THINGSBOARD_ADMIN_CUSTOMER_ID', '13814000-1dd2-11b2-8080-808080808080'),
            ],
        ],

        'test' => [
            'base_uri' => env('THINGSBOARD_TEST_BASE_URI', 'localhost:8080'),
            'admin' => [
                'mail' => env('THINGSBOARD_TEST_ADMIN_MAIL', 'sysadmin@thingsboard.org'),
                'pass' => env('THINGSBOARD_TEST_ADMIN_PASS', 'sysadmin'),
                //                'user_id' => env('THINGSBOARD_TEST_ADMIN_USER_ID', 'cf8f9240-ed9b-11ed-9c52-f37143f58f87'),
                //                'tenant_id' => env('THINGSBOARD_TEST_ADMIN_TENANT_ID', '13814000-1dd2-11b2-8080-808080808080'),
                //                'customer_id' => env('THINGSBOARD_TEST_ADMIN_CUSTOMER_ID', '13814000-1dd2-11b2-8080-808080808080'),
            ],
        ],
    ],
];
