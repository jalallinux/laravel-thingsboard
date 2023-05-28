<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACCESS_TOKEN()
 * @method static self X509_CERTIFICATE()
 * @method static self MQTT_BASIC()
 */
class EnumDeviceCredentialsType extends Enum
{
    protected static function values(): array
    {
        return [
            'ACCESS_TOKEN' => 'ACCESS_TOKEN',
            'X509_CERTIFICATE' => 'X509_CERTIFICATE',
            'MQTT_BASIC' => 'MQTT_BASIC',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACCESS_TOKEN' => 'Access Token',
            'X509_CERTIFICATE' => 'X509 Certificate',
            'MQTT_BASIC' => 'MQTT Basic',
        ];
    }
}
