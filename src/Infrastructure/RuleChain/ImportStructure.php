<?php

namespace JalalLinuX\Thingsboard\Infrastructure\RuleChain;

use Jenssegers\Model\Model;

/**
 * @property array $ruleChains
 * @property array $metadata
 */
class ImportStructure extends Model
{
    protected $fillable = [
        'ruleChains',
        'metadata',
    ];

    protected $casts = [
        'ruleChains' => 'array',
        'metadata' => 'array',
    ];
}
