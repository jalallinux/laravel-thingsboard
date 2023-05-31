<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SERVER_SCOPE()
 * @method static self SHARED_SCOPE()
 * @method static self CLIENT_SCOPE()
 */
class EnumTelemetryScope extends Enum
{
    protected static function values(): array
    {
        return [
            'SERVER_SCOPE' => 'SERVER_SCOPE',
            'SHARED_SCOPE' => 'SHARED_SCOPE',
            'CLIENT_SCOPE' => 'CLIENT_SCOPE',
        ];
    }

    protected static function labels(): array
    {
        return [
            'SERVER_SCOPE' => 'Server Scope',
            'SHARED_SCOPE' => 'Shared Scope',
            'CLIENT_SCOPE' => 'Client Scope',
        ];
    }
}
