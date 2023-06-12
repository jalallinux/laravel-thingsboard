<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACK_TS()
 * @method static self CLEAR_TS()
 * @method static self CREATED_TIME()
 * @method static self END_TS()
 * @method static self SEVERITY()
 * @method static self START_TS()
 * @method static self STATUS()
 * @method static self TYPE()
 */
class EnumAlarmSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'ACK_TS' => 'ackTs',
            'CLEAR_TS' => 'clearTs',
            'CREATED_TIME' => 'createdTime',
            'END_TS' => 'endTs',
            'SEVERITY' => 'severity',
            'START_TS' => 'startTs',
            'STATUS' => 'status',
            'TYPE' => 'type',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACK_TS' => 'Ack Ts',
            'CLEAR_TS' => 'Clear Ts',
            'CREATED_TIME' => 'Created Time',
            'END_TS' => 'End Ts',
            'SEVERITY' => 'Severity',
            'START_TS' => 'Start Ts',
            'STATUS' => 'Status',
            'TYPE' => 'Type',
        ];
    }
}
