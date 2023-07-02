<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ASC()
 * @method static self DESC()
 */
class EnumSortOrder extends BaseEnum
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
