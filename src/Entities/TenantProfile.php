<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property string $default;
 * @property Id $id;
 * @property string $name;
 * @property string $description;
 * @property bool $isolatedTbRuleEngine;
 * @property array $profileData;
 */
class TenantProfile extends Tntity
{
    protected $fillable = [
        'default',
        'id',
        'name',
        'description',
        'isolatedTbRuleEngine',
        'profileData',
    ];

    protected $casts = [
        'default' => 'boolean',
        'id' => CastId::class,
        'isolatedTbRuleEngine' => 'boolean',
        'profileData' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::TENANT_PROFILE();
    }
}
