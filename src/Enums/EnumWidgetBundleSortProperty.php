<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self TENANT_ID()
 * @method static self TITLE()
 */
class EnumWidgetBundleSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'TENANT_ID' => 'tenantId',
            'TITLE' => 'title',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created time',
            'TENANT_ID' => 'Tenant id',
            'TITLE' => 'Title',
        ];
    }
}
