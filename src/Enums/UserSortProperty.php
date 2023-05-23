<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CREATED_TIME()
 * @method static self EMAIL()
 * @method static self FIRSTNAME()
 * @method static self LASTNAME()
 */
class UserSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'EMAIL' => 'email',
            'FIRSTNAME' => 'firstName',
            'LASTNAME' => 'lastName',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'EMAIL' => 'Email',
            'FIRSTNAME' => 'First Name',
            'LASTNAME' => 'Last Name',
        ];
    }
}
