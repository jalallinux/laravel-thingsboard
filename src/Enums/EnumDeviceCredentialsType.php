<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACCESS_TOKEN()
 */
class EnumDeviceCredentialsType extends Enum
{
    protected static function values(): array
    {
        return [
            'ACCESS_TOKEN' => 'ACCESS_TOKEN',
            '' => '',
            '' => '',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACCESS_TOKEN' => 'Access Token',
            '' => '',
            '' => '',
        ];
    }
}
