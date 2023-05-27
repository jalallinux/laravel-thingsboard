<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self TENANT_ADMIN()
 * @method static self SYS_ADMIN()
 * @method static self CUSTOMER_USER()
 */
class ThingsboardAuthority extends Enum
{
    protected static function values(): array
    {
        return [
            'TENANT_ADMIN' => 'TENANT_ADMIN',
            'SYS_ADMIN' => 'SYS_ADMIN',
            'CUSTOMER_USER' => 'CUSTOMER_USER',
        ];
    }

    protected static function labels(): array
    {
        return [
            'TENANT_ADMIN' => 'Tenant Admin',
            'SYS_ADMIN' => 'System Admin',
            'CUSTOMER_USER' => 'Customer User',
        ];
    }
}
