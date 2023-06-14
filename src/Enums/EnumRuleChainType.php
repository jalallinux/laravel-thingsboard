<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self CORE()
 * @method static self EDGE()
 */
class EnumRuleChainType extends Enum
{
    protected static function values(): array
    {
        return [
            'CORE' => 'CORE',
            'EDGE' => 'EDGE'
        ];
    }

    protected static function labels(): array
    {
        return [
            'CORE' => 'Core',
            'EDGE' => 'Edge'
        ];
    }
}
