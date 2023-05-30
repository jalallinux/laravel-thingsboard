<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self COAP()
 * @method static self DEFAULT()
 * @method static self LWM2M()
 * @method static self MQTT()
 * @method static self SNMP()
 */
class EnumDeviceProfileTransportType extends Enum
{
    protected static function values(): array
    {
        return [
            'COAP' => 'COAP',
            'DEFAULT' => 'DEFAULT',
            'LWM2M' => 'LWM2M',
            'MQTT' => 'MQTT',
            'SNMP' => 'SNMP',
        ];
    }

    protected static function labels(): array
    {
        return [
            'COAP' => 'Coap',
            'DEFAULT' => 'Default',
            'LWM2M' => 'Lwm2m',
            'MQTT' => 'Mqtt',
            'SNMP' => 'Snmp',
        ];
    }
}
