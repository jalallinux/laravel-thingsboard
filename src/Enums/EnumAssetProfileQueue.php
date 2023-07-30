<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self HIGH_PRIORITY()
 * @method static self MAIN()
 * @method static self SEQUENTIAL_BY_ORIGINATOR()
 */
class EnumAssetProfileQueue extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'HIGH_PRIORITY' => 'HighPriority',
            'MAIN' => 'Main',
            'SEQUENTIAL_BY_ORIGINATOR' => 'SequentialByOriginator',
        ];
    }

    protected static function labels(): array
    {
        return [
            'HIGH_PRIORITY' => 'HighPriority',
            'MAIN' => 'Main',
            'SEQUENTIAL_BY_ORIGINATOR' => 'SequentialByOriginator',
        ];
    }
}
