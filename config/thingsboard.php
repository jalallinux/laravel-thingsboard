<?php

use JalalLinuX\Thingsboard\Enums\EnumDefaultWidgetTypeDescriptor;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Repositories\MemoryRepository;

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

    'cache' => [
        'prefix' => '_thingsboard_',
        'driver' => env('THINGSBOARD_CACHE_DRIVER', 'redis'),
    ],

    'temp_path' => storage_path('app/public/thingsboard'),

    'default_widget_type_descriptors' => [
        [
            'enum' => EnumDefaultWidgetTypeDescriptor::TIME_SERIES()->value,
            'isSystem' => true,
            'bundleAlias' => 'charts',
            'alias' => 'basic_timeseries',
        ],
        [
            'enum' => EnumDefaultWidgetTypeDescriptor::ATTRIBUTES_CARD()->value,
            'isSystem' => true,
            'bundleAlias' => 'cards',
            'alias' => 'attributes_card',
        ],
        [
            'enum' => EnumDefaultWidgetTypeDescriptor::GPIO_CONTROL()->value,
            'isSystem' => true,
            'bundleAlias' => 'gpio_widgets',
            'alias' => 'basic_gpio_control',
        ],
        [
            'enum' => EnumDefaultWidgetTypeDescriptor::ALARMS_TABLE()->value,
            'isSystem' => true,
            'bundleAlias' => 'alarm_widgets',
            'alias' => 'alarms_table',
        ],
        [
            'enum' => EnumDefaultWidgetTypeDescriptor::HTML_CARD()->value,
            'isSystem' => true,
            'bundleAlias' => 'cards',
            'alias' => 'html_card',
        ],
    ],

    'rest' => [
        'schema' => env('THINGSBOARD_REST_SCHEMA', 'http'),
        'host' => env('THINGSBOARD_REST_HOST', 'localhost'),
        'port' => env('THINGSBOARD_REST_PORT', 9090),

        'authorization' => [
            'header_key' => 'X-Authorization',
            'token_type' => 'Bearer',
        ],

        'users' => [
            [
                'role' => \JalalLinuX\Thingsboard\Enums\EnumAuthority::SYS_ADMIN()->value,
                'mail' => env('THINGSBOARD_ADMIN_MAIL', 'sysadmin@thingsboard.org'),
                'pass' => env('THINGSBOARD_ADMIN_PASS', 'sysadmin'),
            ],
            [
                'role' => \JalalLinuX\Thingsboard\Enums\EnumAuthority::TENANT_ADMIN()->value,
                'mail' => env('THINGSBOARD_TENANT_MAIL', 'tenant@thingsboard.org'),
                'pass' => env('THINGSBOARD_TENANT_PASS', 'tenant'),
            ],
            [
                'role' => \JalalLinuX\Thingsboard\Enums\EnumAuthority::CUSTOMER_USER()->value,
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
                'expirationTime' => 'now + 1 minute',

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

    'mqtt' => [
        // The host and port to which the client shall connect.
        'host' => env('THINGSBOARD_MQTT_HOST', 'localhost'),
        'port' => env('THINGSBOARD_MQTT_PORT', 1883),

        // Thingsboard default topics
        'topics' => [
            'request' => 'v1/devices/me/rpc/request/+',
            'attribute' => 'v1/devices/me/attributes',
            'telemetry' => 'v1/devices/me/telemetry',
        ],

        // The MQTT protocol version used for the connection.
        'protocol' => MqttClient::MQTT_3_1,

        // Defines which repository implementation shall be used. Currently,
        // only a MemoryRepository is supported.
        'repository' => MemoryRepository::class,

        // Additional settings used for the connection to the broker.
        // All of these settings are entirely optional and have sane defaults.
        'connection_settings' => [

            // The TLS settings used for the connection. Must match the specified port.
            'tls' => [
                'enabled' => env('THINGSBOARD_MQTT_TLS_ENABLED', false),
                'allow_self_signed_certificate' => env('THINGSBOARD_MQTT_TLS_ALLOW_SELF_SIGNED_CERT', false),
                'verify_peer' => env('THINGSBOARD_MQTT_TLS_VERIFY_PEER', true),
                'verify_peer_name' => env('THINGSBOARD_MQTT_TLS_VERIFY_PEER_NAME', true),
                'ca_file' => env('THINGSBOARD_MQTT_TLS_CA_FILE'),
                'ca_path' => env('THINGSBOARD_MQTT_TLS_CA_PATH'),
                'client_certificate_file' => env('THINGSBOARD_MQTT_TLS_CLIENT_CERT_FILE'),
                'client_certificate_key_file' => env('THINGSBOARD_MQTT_TLS_CLIENT_CERT_KEY_FILE'),
                'client_certificate_key_passphrase' => env('THINGSBOARD_MQTT_TLS_CLIENT_CERT_KEY_PASSPHRASE'),
            ],

            // Credentials used for authentication and authorization.
            'auth' => [
                'username' => env('THINGSBOARD_MQTT_AUTH_USERNAME'),
                'password' => env('THINGSBOARD_MQTT_AUTH_PASSWORD'),
            ],

            // Can be used to declare a last will during connection. The last will
            // is published by the broker when the client disconnects abnormally
            // (e.g. in case of a disconnect).
            'last_will' => [
                'topic' => env('THINGSBOARD_MQTT_LAST_WILL_TOPIC'),
                'message' => env('THINGSBOARD_MQTT_LAST_WILL_MESSAGE'),
                'quality_of_service' => env('THINGSBOARD_MQTT_LAST_WILL_QUALITY_OF_SERVICE', 0),
                'retain' => env('THINGSBOARD_MQTT_LAST_WILL_RETAIN', false),
            ],

            // The timeouts (in seconds) used for the connection. Some of these settings
            // are only relevant when using the event loop of the MQTT client.
            'connect_timeout' => env('THINGSBOARD_MQTT_CONNECT_TIMEOUT', 60),
            'socket_timeout' => env('THINGSBOARD_MQTT_SOCKET_TIMEOUT', 5),
            'resend_timeout' => env('THINGSBOARD_MQTT_RESEND_TIMEOUT', 10),

            // The interval (in seconds) in which the client will send a ping to the broker,
            // if no other message has been sent.
            'keep_alive_interval' => env('THINGSBOARD_MQTT_KEEP_ALIVE_INTERVAL', 10),

            // Additional settings for the optional auto-reconnect. The delay between reconnect attempts is in seconds.
            'auto_reconnect' => [
                'enabled' => env('THINGSBOARD_MQTT_AUTO_RECONNECT_ENABLED', false),
                'max_reconnect_attempts' => env('THINGSBOARD_MQTT_AUTO_RECONNECT_MAX_RECONNECT_ATTEMPTS', 3),
                'delay_between_reconnect_attempts' => env('THINGSBOARD_MQTT_AUTO_RECONNECT_DELAY_BETWEEN_RECONNECT_ATTEMPTS', 0),
            ],

        ],
    ],
];
