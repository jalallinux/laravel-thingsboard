<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self CRITICAL()
 * @method static self INDETERMINATE()
 * @method static self MAJOR()
 * @method static self MINOR()
 * @method static self WARNING()
 */
class EnumAlarmSeverityList extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'CRITICAL' => 'CRITICAL',
            'INDETERMINATE' => 'INDETERMINATE',
            'MAJOR' => 'MAJOR',
            'MINOR' => 'MINOR',
            'WARNING' => 'WARNING',
        ];
    }

    protected static function labels(): array
    {
        return [
            'CRITICAL' => 'Critical',
            'INDETERMINATE' => 'Indeterminate',
            'MAJOR' => 'Major',
            'MINOR' => 'Minor',
            'WARNING' => 'Warning',
        ];
    }
}
