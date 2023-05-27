<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ASC()
 * @method static self DESC()
 */
class EnumThingsboardSortOrder extends Enum
{
    protected static function values(): array
    {
        return [
            'ASC' => 'ASC',
            'DESC' => 'DESC',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ASC' => 'Ascending',
            'DESC' => 'Descending',
        ];
    }
}
