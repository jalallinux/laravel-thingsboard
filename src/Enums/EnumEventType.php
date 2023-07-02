<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self DEBUG_RULE_CHAIN()
 * @method static self DEBUG_RULE_NODE()
 * @method static self ERROR()
 * @method static self LC_EVENT()
 * @method static self STATS()
 */
class EnumEventType extends BaseEnum
{
    protected static function values(): array
    {
        //, , , ,
        return [
            'DEBUG_RULE_CHAIN' => 'DEBUG_RULE_CHAIN',
            'DEBUG_RULE_NODE' => 'DEBUG_RULE_NODE',
            'ERROR' => 'ERROR',
            'LC_EVENT' => 'LC_EVENT',
            'STATS' => 'STATS',
        ];
    }

    protected static function labels(): array
    {
        return [
            'DEBUG_RULE_CHAIN' => 'Debug Rule Chain',
            'DEBUG_RULE_NODE' => 'Debug Rule Node',
            'ERROR' => 'Error',
            'LC_EVENT' => 'Lc Event',
            'STATS' => 'Stats',
        ];
    }
}
