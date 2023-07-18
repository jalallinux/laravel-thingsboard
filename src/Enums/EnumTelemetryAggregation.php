<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self AVG()
 * @method static self COUNT()
 * @method static self MAX()
 * @method static self MIN()
 * @method static self NONE()
 * @method static self SUM()
 */
class EnumTelemetryAggregation extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'AVG' => 'AVG',
            'COUNT' => 'COUNT',
            'MAX' => 'MAX',
            'MIN' => 'MIN',
            'NONE' => 'NONE',
            'SUM' => 'SUM',
        ];
    }

    protected static function labels(): array
    {
        return [
            'AVG' => 'avg',
            'COUNT' => 'count',
            'MAX' => 'max',
            'MIN' => 'min',
            'NONE' => 'none',
            'SUM' => 'sum',
        ];
    }
}
