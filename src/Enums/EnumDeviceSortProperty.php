<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CREATED_TIME()
 * @method static self CUSTOMER_TITLE()
 * @method static self DEVICE_PROFILE_NAME()
 * @method static self LABEL()
 * @method static self NAME()
 */
class EnumDeviceSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'CUSTOMER_TITLE' => 'customerTitle',
            'DEVICE_PROFILE_NAME' => 'deviceProfileName',
            'LABEL' => 'label',
            'NAME' => 'name',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'CUSTOMER_TITLE' => 'Customer Title',
            'DEVICE_PROFILE_NAME' => 'Device Profile Name',
            'LABEL' => 'Label',
            'NAME' => 'Name',
        ];
    }
}
