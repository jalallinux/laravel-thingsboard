<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTenantSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id;
 * @property Id $tenantProfileId
 * @property \DateTime $createdTime
 * @property string $title
 * @property string $name
 * @property string $region
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $address
 * @property string $address2
 * @property string $zip
 * @property string $phone
 * @property string $email
 * @property array $additionalInfo
 * @property string $tenantProfileName
 */
class Tenant extends Tntity
{
    protected $fillable = [
        'id',
        'tenantProfileId',
        'createdTime',
        'title',
        'name',
        'region',
        'country',
        'state',
        'city',
        'address',
        'address2',
        'zip',
        'phone',
        'email',
        'additionalInfo',
        'tenantProfileName',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantProfileId' => CastId::class,
        'createdTime' => 'timestamp',
        'additionalInfo' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::TENANT();
    }

    /**
     * Returns a page of tenant info objects registered in the platform.
     * The Tenant Info object extends regular Tenant object and includes Tenant Profile name.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     *
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenants(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumTenantSortProperty::class, [EnumTenantSortProperty::TENANT_PROFILE_NAME()]);

        $response = $this->api()->get('tenants', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of tenant info objects registered in the platform.
     * The Tenant Info object extends regular Tenant object and includes Tenant Profile name.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     *
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantInfos(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumTenantSortProperty::class);

        $response = $this->api()->get('tenantInfos', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Fetch the Tenant Info object based on the provided Tenant ID.
     * The Tenant Info object extends regular Tenant object and includes Tenant Profile name.
     *
     * @param string|null $id
     *
     * @return self
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getTenantInfoById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $tenant = $this->api()->get("tenant/info/{$id}")->json();

        return tap($this, fn () => $this->fill($tenant));
    }

    /**
     * Deletes the tenant, it's customers, rule chains, devices and all other related entities.
     * Referencing non-existing tenant ID will cause an error.
     *
     * @param string|null $id
     *
     * @return bool
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function deleteTenant(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("tenant/{$id}")->successful();
    }

    /**
     * Create or update the Tenant.
     * When creating tenant, platform generates Tenant ID as time-based UUID.
     * Default Rule Chain and Device profile are also generated for the new tenants automatically.
     * The newly created Tenant ID will be present in the response.
     * Specify existing Tenant ID to update the Tenant.
     * Referencing non-existing Tenant ID will cause 'Not Found' error.Remove 'id', 'tenantId' from the request body example (below) to create new Tenant entity.
     * @param string|null $accessToken
     * @param string|null $tenantProfileId
     * @return $this
     * @throws \Throwable
     * @author JalalLinuX
     * @group SYS_ADMIN
     */
    public function saveTenant(string $accessToken = null, string $tenantProfileId = null): static
    {
        $tenantProfileId = $tenantProfileId ?? $this->tenantProfileId->id ?? TenantProfile::instance()->withUser($this->_thingsboardUser)->getDefaultTenantProfileInfo()->id->id;

        $payload = array_merge($this->getAttributes(), [
            'title' => $this->forceAttribute('title'),
            'tenantProfileId' => new Id($tenantProfileId, EnumEntityType::TENANT_PROFILE()),
        ]);

        $tenant = $this->api()->post("tenant", $payload)->json();

        return tap($this, fn () => $this->fill($tenant));
    }
}
