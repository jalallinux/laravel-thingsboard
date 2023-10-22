<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $tenantId
 * @property Id $customerId
 * @property Id $rootRuleChainId
 * @property \DateTime $createdTime
 * @property string $name
 * @property string $type
 * @property string $label
 * @property string $routingKey
 * @property string $secret
 * @property string $customerTitle
 * @property bool $customerIsPublic
 */
class Edge extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'customerId',
        'rootRuleChainId',
        'name',
        'type',
        'label',
        'routingKey',
        'secret',
        'customerIsPublic',
        'customerTitle',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'rootRuleChainId' => CastId::class,
        'additionalInfo' => 'array',
        'createdTime' => 'timestamp',
        'customerIsPublic' => 'boolean',
    ];
    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::EDGE();
    }
}
