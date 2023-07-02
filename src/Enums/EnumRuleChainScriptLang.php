<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self JS()
 * @method static self TBEL()
 */
class EnumRuleChainScriptLang extends BaseEnum
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
