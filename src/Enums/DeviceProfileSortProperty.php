<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self TRANSPORT_TYPE()
 * @method static self CREATED_TIME()
 * @method static self DESCRIPTION()
 * @method static self IS_DEFAULT()
 * @method static self NAME()
 * @method static self TYPE()
 */
class DeviceProfileSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'TRANSPORT_TYPE' => 'transportType',
            'CREATED_TIME' => 'createdTime',
            'DESCRIPTION' => 'description',
            'IS_DEFAULT' => 'isDefault',
            'NAME' => 'name',
            'TYPE' => 'type',
        ];
    }

    protected static function labels(): array
    {
        return [
            'TRANSPORT_TYPE' => 'Transport Type',
            'CREATED_TIME' => 'Created Time',
            'DESCRIPTION' => 'Description',
            'IS_DEFAULT' => 'Is Default',
            'NAME' => 'Name',
            'TYPE' => 'Type',
        ];
    }
}
