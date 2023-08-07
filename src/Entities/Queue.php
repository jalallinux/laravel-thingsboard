<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumQueueProcessingStrategyType;
use JalalLinuX\Thingsboard\Enums\EnumQueueSubmitStrategy;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property string $name
 * @property Id $tenantId
 * @property array $additionalInfo
 * @property bool $consumerPerPartition
 * @property \DateTime $createdTime
 * @property double $packProcessingTimeout
 * @property int $partitions
 * @property int $pollInterval
 * @property EnumQueueProcessingStrategyType $processingStrategy
 * @property EnumQueueSubmitStrategy $submitStrategy
 * @property string $topic
 */
class Queue extends Tntity
{
    protected $fillable = [
        'id',
        'name',
        'tenantId',
        'additionalInfo',
        'consumerPerPartition',
        'createdTime',
        'packProcessingTimeout',
        'partitions',
        'pollInterval',
        'processingStrategy',
        'submitStrategy',
        'topic',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'additionalInfo' => 'array',
        'consumerPerPartition' => 'bool',
        'createdTime' => 'timestamp',
        'packProcessingTimeout' => 'int',
        'partitions' => 'int',
        'pollInterval' => 'int',
        'processingStrategy' => EnumQueueProcessingStrategyType::class,
        'submitStrategy' => EnumQueueSubmitStrategy::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }
}
