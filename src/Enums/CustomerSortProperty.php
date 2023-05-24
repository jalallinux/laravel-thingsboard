<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CREATED_TIME()
 * @method static self EMAIL()
 * @method static self TITLE()
 * @method static self COUNTRY()
 * @method static self CITY()
 */
class CustomerSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'EMAIL' => 'email',
            'TITLE' => 'title',
            'COUNTRY' => 'country',
            'CITY' => 'city',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'EMAIL' => 'Email',
            'TITLE' => 'Title',
            'COUNTRY' => 'country',
            'CITY' => 'city',
        ];
    }
}
