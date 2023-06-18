<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ALARM_FIELD()
 * @method static self ATTRIBUTE()
 * @method static self CLIENT_ATTRIBUTE()
 * @method static self ENTITY_FIELD()
 * @method static self SERVER_ATTRIBUTE()
 * @method static self SHARED_ATTRIBUTE()
 * @method static self TIME_SERIES()
 */
class EnumQueryEntitySortKeyFilterTypes extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'ALARM_FIELD' => 'ALARM_FIELD',
            'ATTRIBUTE' => 'ATTRIBUTE',
            'CLIENT_ATTRIBUTE' => 'CLIENT_ATTRIBUTE',
            'ENTITY_FIELD' => 'ENTITY_FIELD',
            'SERVER_ATTRIBUTE' => 'SERVER_ATTRIBUTE',
            'SHARED_ATTRIBUTE' => 'SHARED_ATTRIBUTE',
            'TIME_SERIES' => 'TIME_SERIES',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ALARM_FIELD' => 'Alarm Field',
            'ATTRIBUTE' => 'Attribute',
            'CLIENT_ATTRIBUTE' => 'Client Attribute',
            'ENTITY_FIELD' => 'Entity Filed',
            'SERVER_ATTRIBUTE' => 'Server Attribute',
            'SHARED_ATTRIBUTE' => 'Shared Attribute',
            'TIME_SERIES' => 'Time Series',
        ];
    }
}
