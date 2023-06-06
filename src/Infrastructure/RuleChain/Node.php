<?php

namespace JalalLinuX\Thingsboard\Infrastructure\RuleChain;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use Jenssegers\Model\Model;
use Vkovic\LaravelCustomCasts\HasCustomCasts;

/**
 * @property Id $externalId
 * @property Id $id
 * @property \DateTime $createdTime
 * @property Id $ruleChainId
 * @property string $type
 * @property string $name
 * @property boolean $debugMode
 * @property boolean $singletonMode
 * @property array $additionalInfo
 * @property array $configuration
 */
class Node extends Model
{
    use HasCustomCasts;

    protected $fillable = [
        'externalId',
        'id',
        'createdTime',
        'ruleChainId',
        'type',
        'name',
        'debugMode',
        'singletonMode',
        'additionalInfo',
        'configuration',
    ];

    protected $casts = [
        'externalId' => CastId::class,
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'ruleChainId' => CastId::class,
        'debugMode' => 'boolean',
        'singletonMode' => 'boolean',
        'additionalInfo' => 'array',
        'configuration' => 'array',
    ];
}
