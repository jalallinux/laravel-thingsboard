<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self TIME_SERIES()
 * @method static self ATTRIBUTES_CARD()
 * @method static self GPIO_CONTROL()
 * @method static self ALARMS_TABLE()
 * @method static self HTML_CARD()
 * @method static self RPC()
 * @method static self LATEST()
 * @method static self TIMESERIES()
 */
class EnumDefaultWidgetTypeDescriptor extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'TIME_SERIES' => 'time_series',
            'ATTRIBUTES_CARD' => 'attributes_card',
            'GPIO_CONTROL' => 'gpio_control',
            'ALARMS_TABLE' => 'alarms_table',
            'HTML_CARD' => 'html_card',
            'RPC' => 'rpc',
            'LATEST' => 'latest',
            'TIMESERIES' => 'timeseries',
        ];
    }

    protected static function labels(): array
    {
        return [
            'TIME_SERIES' => 'Time series',
            'ATTRIBUTES_CARD' => 'Attributes card',
            'GPIO_CONTROL' => 'GPIO control',
            'ALARMS_TABLE' => 'Alarms table',
            'HTML_CARD' => 'HTML card',
            'RPC' => 'Rpc',
            'LATEST' => 'Latest',
            'TIMESERIES' => 'Timeseries',
        ];
    }
}
