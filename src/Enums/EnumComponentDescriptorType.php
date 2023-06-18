<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ACTION()
 * @method static self ENRICHMENT()
 * @method static self EXTERNAL()
 * @method static self FILTER()
 * @method static self FLOW()
 * @method static self TRANSFORMATION()
 */
class EnumComponentDescriptorType extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'ACTION' => 'ACTION',
            'ENRICHMENT' => 'ENRICHMENT',
            'EXTERNAL' => 'EXTERNAL',
            'FILTER' => 'FILTER',
            'FLOW' => 'FLOW',
            'TRANSFORMATION' => 'TRANSFORMATION',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACTION' => 'Action',
            'ENRICHMENT' => 'Enrichment',
            'EXTERNAL' => 'External',
            'FILTER' => 'Filter',
            'FLOW' => 'Flow',
            'TRANSFORMATION' => 'Transformation',
        ];
    }
}
