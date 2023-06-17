<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACCESS_TOKEN()
 * @method static self REFRESH_TOKEN()
 */
class EnumTokenType extends Enum
{
    protected static function values(): array
    {
        return [
            'ACCESS_TOKEN' => 'accessToken',
            'REFRESH_TOKEN' => 'refreshToken',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACCESS_TOKEN' => 'Access Token',
            'REFRESH_TOKEN' => 'Refresh Token',
        ];
    }
}
