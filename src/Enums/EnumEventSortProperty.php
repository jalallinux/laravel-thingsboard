<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ID()
 * @method static self TS()
 */
class EnumEventSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'ID' => 'id',
            'TS' => 'ts'
        ];
    }

    protected static function labels(): array
    {
        return [
            'ID' => 'Id',
            'TS' => 'Ts'
        ];
    }
}
