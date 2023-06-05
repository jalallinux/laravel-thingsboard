<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CREATED_TIME()
 * @method static self CUSTOMER_TITLE()
 * @method static self LABEL()
 * @method static self NAME()
 * @method static self TYPE()
 */
class EnumAssetSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'CUSTOMER_TITLE' => 'customerTitle',
            'LABEL' => 'label',
            'NAME' => 'name',
            'TYPE' => 'type',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'CUSTOMER_TITLE' => 'Customer Title',
            'LABEL' => 'Label',
            'NAME' => 'Name',
            'TYPE' => 'Type',
        ];
    }
}
