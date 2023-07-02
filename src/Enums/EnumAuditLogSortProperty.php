<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self ACTION_STATUS()
 * @method static self ACTION_TYPE()
 * @method static self ENTITY_NAME()
 * @method static self ENTITY_TYPE()
 * @method static self USER_NAME()
 */
class EnumAuditLogSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'ACTION_STATUS' => 'actionStatus',
            'ACTION_TYPE' => 'actionType',
            'ENTITY_NAME' => 'entityName',
            'ENTITY_TYPE' => 'entityType',
            'USER_NAME' => 'userName',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created time',
            'ACTION_STATUS' => 'Action status',
            'ACTION_TYPE' => 'Action type',
            'ENTITY_NAME' => 'Entity name',
            'ENTITY_TYPE' => 'Entity type',
            'USER_NAME' => 'User name',
        ];
    }
}
