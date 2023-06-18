<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self BOOLEAN()
 * @method static self DATE_TIME()
 * @method static self NUMERIC()
 * @method static self STRING()
 */
class EnumQueryEntityKeyFilterPredicate extends BaseEnum
{
    protected static function values(): array
    {
        //, , ,
        return [
            'BOOLEAN' => 'BOOLEAN',
            'DATE_TIME' => 'DATE_TIME',
            'NUMERIC' => 'NUMERIC',
            'STRING' => 'STRING',
        ];
    }

    protected static function labels(): array
    {
        return [
            'BOOLEAN' => 'Boolean',
            'DATE_TIME' => 'Date Time',
            'NUMERIC' => 'Numeric',
            'STRING' => 'String',
        ];
    }
}
