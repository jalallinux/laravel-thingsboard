<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self NAME()
 * @method static self TOPIC()
 */
class EnumQueueSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'NAME' => 'name',
            'TOPIC' => 'topic',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'NAME' => 'Name',
            'TOPIC' => 'Topic',
        ];
    }
}
