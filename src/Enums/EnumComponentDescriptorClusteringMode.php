<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ENABLED()
 * @method static self SINGLETON()
 * @method static self USER_PREFERENCE()
 */
class EnumComponentDescriptorClusteringMode extends BaseEnum
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
