<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Casts\CastTenantProfileDataConfiguration;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTenantProfileSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\ProfileData;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property bool $default;
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
        'default' => 'bool',
        'id' => CastId::class,
        'isolatedTbRuleEngine' => 'bool',
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
     * @param string|null $id
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


    /**
     * Fetch the default Tenant Profile Info object based.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
     *
     * @param bool $full
     *
     * @return $this
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getDefaultTenantProfileInfo(bool $full = false): static
    {
        $tenantProfile = $this->api()->get('tenantProfileInfo/default')->json();

        if ($full) {
            return $this->getTenantProfileById($tenantProfile['id']['id']);
        }

        return tap($this, fn () => $this->fill($tenantProfile));
    }

    /**
     * Returns a page of tenant profile info objects registered in the platform.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param PaginationArguments $paginationArguments
     *
     * @return PaginatedResponse
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantProfileInfos(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumTenantProfileSortProperty::class);

        $response = $this->api()->get('tenantProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
