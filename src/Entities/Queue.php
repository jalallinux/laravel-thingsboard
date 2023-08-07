<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumQueueProcessingStrategyType;
use JalalLinuX\Thingsboard\Enums\EnumQueueServiceType;
use JalalLinuX\Thingsboard\Enums\EnumQueueSortProperty;
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
 * @property float $packProcessingTimeout
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

    /**
     * Returns a page of queues registered in the platform.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  EnumQueueServiceType  $serviceType
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getTenantQueuesByServiceType(PaginationArguments $paginationArguments, EnumQueueServiceType $serviceType): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumQueueSortProperty::class);

        $response = $this->api()->get('queues', $paginationArguments->queryParams([
            'serviceType' => $serviceType->value,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
