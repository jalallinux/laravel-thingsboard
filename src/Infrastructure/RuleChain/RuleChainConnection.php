<?php

namespace JalalLinuX\Thingsboard\Infrastructure\RuleChain;

use JalalLinuX\Thingsboard\Casts\CastId;
use Jenssegers\Model\Model;
use Vkovic\LaravelCustomCasts\HasCustomCasts;

/**
 * @property int $fromIndex
 * @property int $targetRuleChainId
 * @property string $additionalInfo
 * */
class RuleChainConnection extends Model
{
    use HasCustomCasts;

    protected $fillable = [
        'fromIndex',
        'targetRuleChainId',
        'additionalInfo',
        'type',
    ];

    protected $casts = [
        'fromIndex' => 'integer',
        'targetRuleChainId' => CastId::class,
        'additionalInfo' => 'array',
    ];
}
