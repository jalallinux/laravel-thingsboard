<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ALLOW_CREATE_NEW_DEVICES()
 * @method static self CHECK_PRE_PROVISIONED_DEVICES()
 * @method static self DISABLED()
 * @method static self X509_CERTIFICATE_CHAIN()
 */
class EnumDeviceProfileProvisionType extends Enum
{
    protected static function values(): array
    {
        return [
            'ALLOW_CREATE_NEW_DEVICES' => 'ALLOW_CREATE_NEW_DEVICES',
            'CHECK_PRE_PROVISIONED_DEVICES' => 'CHECK_PRE_PROVISIONED_DEVICES',
            'DISABLED' => 'DISABLED',
            'X509_CERTIFICATE_CHAIN' => 'X509_CERTIFICATE_CHAIN',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ALLOW_CREATE_NEW_DEVICES' => 'Allow Create New Devices',
            'CHECK_PRE_PROVISIONED_DEVICES' => 'Check Pre Provisioned Devices',
            'DISABLED' => 'Disabled',
            'X509_CERTIFICATE_CHAIN' => 'X509 Certificate Chain',
        ];
    }
}
