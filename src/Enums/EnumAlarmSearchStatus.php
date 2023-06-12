<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACK()
 * @method static self ACTIVE()
 * @method static self ANY()
 * @method static self CLEARED()
 * @method static self UNACK()
 */
class EnumAlarmSearchStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'ACK' => 'ACK',
            'ACTIVE' => 'ACTIVE',
            'ANY' => 'ANY',
            'CLEARED' => 'CLEARED',
            'UNACK' => 'UNACK',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACK' => 'Ack',
            'ACTIVE' => 'Active',
            'ANY' => 'Any',
            'CLEARED' => 'Cleared',
            'UNACK' => 'Unack',
        ];
    }
}
