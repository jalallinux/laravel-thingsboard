<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self DESCRIPTION()
 * @method static self IS_DEFAULT()
 * @method static self NAME()
 */
class EnumTenantProfileSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'DESCRIPTION' => 'description',
            'IS_DEFAULT' => 'isDefault',
            'NAME' => 'name',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created time',
            'DESCRIPTION' => 'Description',
            'IS_DEFAULT' => 'Is default',
            'NAME' => 'name',
        ];
    }
}
