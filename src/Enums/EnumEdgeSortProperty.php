<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self NAME()
 * @method static self CUSTOMER_TITLE()
 * @method static self LABEL()
 * @method static self TYPE()
 */
class EnumEdgeSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'NAME' => 'name',
            'CUSTOMER_TITLE' => 'customerTitle',
            'LABEL' => 'label',
            'TYPE' => 'type',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created time',
            'NAME' => 'Name',
            'CUSTOMER_TITLE' => 'Customer Title',
            'LABEL' => 'Label',
            'TYPE' => 'Type',
        ];
    }
}
