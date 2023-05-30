<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self GENERAL()
 * @method static self MAIL()
 */
class EnumAdminSettingsKey extends Enum
{
    protected static function values(): array
    {
        return [
            'GENERAL' => 'general',
            'MAIL' => 'mail'
        ];
    }

    protected static function labels(): array
    {
        return [
            'GENERAL' => 'General',
            'MAIL' => 'Mail'
        ];
    }
}
