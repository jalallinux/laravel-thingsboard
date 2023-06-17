<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ENABLED()
 * @method static self SINGLETON()
 * @method static self USER_PREFERENCE()
 */
class EnumComponentDescriptorClusteringMode extends Enum
{
    protected static function values(): array
    {
        //, ,
        return [
            'ENABLED' => 'ENABLED',
            'SINGLETON' => 'SINGLETON',
            'USER_PREFERENCE' => 'USER_PREFERENCE',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ENABLED' => 'Enabled',
            'SINGLETON' => 'Singleton',
            'USER_PREFERENCE' => 'User Preference',
        ];
    }
}
