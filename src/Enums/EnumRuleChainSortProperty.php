<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CREATED_TIME()
 * @method static self NAME()
 * @method static self ROOT()
 */
class EnumRuleChainSortProperty extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CREATED_TIME' => 'createdTime',
            'NAME' => 'name',
            'ROOT' => 'root',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CREATED_TIME' => 'Created Time',
            'NAME' => 'Name',
            'ROOT' => 'root',
        ];
    }
}
