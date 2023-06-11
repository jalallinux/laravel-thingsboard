<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACTIVE_ACK()
 * @method static self ACTIVE_UNACK()
 * @method static self CLEARED_ACK()
 * @method static self CLEARED_UNACK()
 */
class EnumAlarmStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'ACTIVE_ACK' => 'ACTIVE_ACK',
            'ACTIVE_UNACK' => 'ACTIVE_UNACK',
            'CLEARED_ACK' => 'CLEARED_ACK',
            'CLEARED_UNACK' => 'CLEARED_UNACK'
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACTIVE_ACK' => 'Active Ack',
            'ACTIVE_UNACK' => 'Active Unack',
            'CLEARED_ACK' => 'Cleared Ack',
            'CLEARED_UNACK' => 'Cleared Unack'
        ];
    }
}
