<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CREATED_TIME()
 * @method static self TITLE()
 */
class EnumDashboardSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'TITLE' => 'title',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created time',
            'TITLE' => 'Title',
        ];
    }
}
