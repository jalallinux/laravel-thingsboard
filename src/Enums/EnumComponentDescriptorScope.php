<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self TENANT()
 */
class EnumComponentDescriptorScope extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'TENANT' => 'TENANT',
        ];
    }

    protected static function labels(): array
    {
        return [
            'TENANT' => 'Tenant',
        ];
    }
}
