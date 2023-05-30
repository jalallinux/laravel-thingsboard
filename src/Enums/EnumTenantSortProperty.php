<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CREATED_TIME()
 * @method static self ADDRESS()
 * @method static self ADDRESS_2()
 * @method static self CITY()
 * @method static self COUNTRY()
 * @method static self EMAIL()
 * @method static self PHONE()
 * @method static self STATE()
 * @method static self TENANT_PROFILE_NAME()
 * @method static self TITLE()
 * @method static self ZIP()
 */
class EnumTenantSortProperty extends Enum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'ADDRESS' => 'address',
            'ADDRESS_2' => 'address2',
            'CITY' => 'city',
            'COUNTRY' => 'country',
            'EMAIL' => 'email',
            'PHONE' => 'phone',
            'STATE' => 'state',
            'TENANT_PROFILE_NAME' => 'tenantProfileName',
            'TITLE' => 'title',
            'ZIP' => 'zip',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'ADDRESS' => 'Address',
            'ADDRESS_2' => 'Address 2',
            'CITY' => 'City',
            'COUNTRY' => 'Country',
            'EMAIL' => 'Email',
            'PHONE' => 'Phone',
            'STATE' => 'State',
            'TENANT_PROFILE_NAME' => 'Tenant Profile Name',
            'TITLE' => 'Title',
            'ZIP' => 'Zip',
        ];
    }
}
