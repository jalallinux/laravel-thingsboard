<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self JS()
 * @method static self TBEL()
 */
class EnumRuleChainScriptLang extends Enum
{
    protected static function values(): array
    {
        return [
            'JS' => 'JS',
            'TBEL' => 'TBEL',
        ];
    }

    protected static function labels(): array
    {
        return [
            'JS' => 'Js',
            'TBEL' => 'Tbel',
        ];
    }
}
