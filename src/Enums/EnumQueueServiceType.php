<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self JS_EXECUTOR()
 * @method static self TB_CORE()
 * @method static self TB_RULE_ENGINE()
 * @method static self TB_TRANSPORT()
 */
class EnumQueueServiceType extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'JS_EXECUTOR' => 'JS_EXECUTOR',
            'TB_CORE' => 'TB_CORE',
            'TB_RULE_ENGINE' => 'TB_RULE_ENGINE',
            'TB_TRANSPORT' => 'TB_TRANSPORT',
        ];
    }

    protected static function labels(): array
    {
        return [
            'JS_EXECUTOR' => 'JS_EXECUTOR',
            'TB_CORE' => 'TB_CORE',
            'TB_RULE_ENGINE' => 'TB_RULE_ENGINE',
            'TB_TRANSPORT' => 'TB_TRANSPORT',
        ];
    }
}
