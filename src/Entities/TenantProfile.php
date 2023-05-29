<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Casts\CastTenantProfileDataConfiguration;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\ProfileData;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property string $default;
 * @property Id $id;
 * @property string $name;
 * @property string $description;
 * @property bool $isolatedTbRuleEngine;
 * @property ProfileData $profileData;
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
        'profileData' => CastTenantProfileDataConfiguration::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::TENANT_PROFILE();
    }

    /**
     * Fetch the Tenant Profile object based on the provided Tenant Profile Id.
     *
     *
     * @return self
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantProfileById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $tenantProfile = $this->api()->get("tenantProfile/{$id}")->json();

        return tap($this, fn () => $this->fill($tenantProfile));
    }
}
