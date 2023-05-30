<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SUCCESS()
 * @method static self FAILURE()
 */
class EnumAuditLogActionStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'SUCCESS' => 'SUCCESS',
            'FAILURE' => 'FAILURE',
        ];
    }

    protected static function labels(): array
    {
        return [
            'SUCCESS' => 'Success',
            'FAILURE' => 'Failure',
        ];
    }
}
