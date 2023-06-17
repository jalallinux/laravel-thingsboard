<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self TENANT()
 */
class EnumComponentDescriptorScope extends Enum
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
