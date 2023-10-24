<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self BATCH()
 * @method static self BURST()
 * @method static self SEQUENTIAL()
 * @method static self SEQUENTIAL_BY_ORIGINATOR()
 * @method static self SEQUENTIAL_BY_TENANT()
 */
class EnumQueueSubmitStrategy extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'BATCH' => 'BATCH',
            'BURST' => 'BURST',
            'SEQUENTIAL' => 'SEQUENTIAL',
            'SEQUENTIAL_BY_ORIGINATOR' => 'SEQUENTIAL_BY_ORIGINATOR',
            'SEQUENTIAL_BY_TENANT' => 'SEQUENTIAL_BY_TENANT',
        ];
    }

    protected static function labels(): array
    {
        return [
            'BATCH' => 'Batch',
            'BURST' => 'Burst',
            'SEQUENTIAL' => 'Sequential',
            'SEQUENTIAL_BY_ORIGINATOR' => 'Sequential by originator',
            'SEQUENTIAL_BY_TENANT' => 'Sequential by tenant',
        ];
    }
}
