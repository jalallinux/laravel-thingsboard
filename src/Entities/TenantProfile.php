<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Casts\CastTenantProfileDataConfiguration;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTenantProfileSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\ProfileData;
use JalalLinuX\Thingsboard\Thingsboard;
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
        'createdTime',
        'profileData',
    ];

    protected $casts = [
        'default' => 'bool',
        'id' => CastId::class,
        'isolatedTbRuleEngine' => 'bool',
        'createdTime' => 'timestamp',
        'profileData' => CastTenantProfileDataConfiguration::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::TENANT_PROFILE();
    }

    /**
     * Create or update the Tenant Profile.
     * When creating tenant profile, platform generates Tenant Profile Id as time-based UUID.
     * The newly created Tenant Profile Id will be present in the response.
     * Specify existing Tenant Profile Id id to update the Tenant Profile. Referencing non-existing Tenant Profile Id will cause 'Not Found' error.
     * Update of the tenant profile configuration will cause immediate recalculation of API limits for all affected Tenants.
     * The 'profileData' object is the part of Tenant Profile that defines API limits and Rate limits.
     * You have an ability to define maximum number of devices ('maxDevice'), assets ('maxAssets') and other entities.
     * You may also define maximum number of messages to be processed per month ('maxTransportMessages', 'maxREExecutions', etc).
     * The '*RateLimit' defines the rate limits using simple syntax. For example, '1000:1,20000:60' means up to 1000 events per second but no more than 20000 event per minute.
     * Let's review the example of tenant profile data below:
     * {
     * "name": "Default",
     * "description": "Default tenant profile",
     * "isolatedTbRuleEngine": false,
     * "profileData": {
     * "configuration": {
     * "type": "DEFAULT",
     * "maxDevices": 0,
     * "maxAssets": 0,
     * "maxCustomers": 0,
     * "maxUsers": 0,
     * "maxDashboards": 0,
     * "maxRuleChains": 0,
     * "maxResourcesInBytes": 0,
     * "maxOtaPackagesInBytes": 0,
     * "transportTenantMsgRateLimit": "1000:1,20000:60",
     * "transportTenantTelemetryMsgRateLimit": "1000:1,20000:60",
     * "transportTenantTelemetryDataPointsRateLimit": "1000:1,20000:60",
     * "transportDeviceMsgRateLimit": "20:1,600:60",
     * "transportDeviceTelemetryMsgRateLimit": "20:1,600:60",
     * "transportDeviceTelemetryDataPointsRateLimit": "20:1,600:60",
     * "maxTransportMessages": 10000000,
     * "maxTransportDataPoints": 10000000,
     * "maxREExecutions": 4000000,
     * "maxJSExecutions": 5000000,
     * "maxDPStorageDays": 0,
     * "maxRuleNodeExecutionsPerMessage": 50,
     * "maxEmails": 0,
     * "maxSms": 0,
     * "maxCreatedAlarms": 1000,
     * "defaultStorageTtlDays": 0,
     * "alarmsTtlDays": 0,
     * "rpcTtlDays": 0,
     * "warnThreshold": 0
     * }
     * },
     * "default": true
     * }
     * Remove 'id', from the request body example (below) to create new Tenant Profile entity.
     * Available for users with 'SYS_ADMIN' authority.
     *
     * @param  string|null  $name
     * @return TenantProfile
     *
     * @author Sabiee
     */
    public function saveTenantProfile(string $name = null): TenantProfile
    {
        $payload = array_merge($this->attributesToArray(), [
            'name' => $name ?? $this->forceAttribute('name'),
        ]);

        $tenantProfile = $this->api()->post('tenantProfile', $payload)->json();

        return $this->fill($tenantProfile);
    }

    /**
     * Deletes the tenant profile.
     * Referencing non-existing tenant profile Id will cause an error.
     * Referencing profile that is used by the tenants will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function deleteTenantProfile(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'tenantProfileId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("tenantProfile/{$id}")->successful();
    }

    /**
     * Fetch the Tenant Profile object based on the provided Tenant Profile Id.
     *
     * @param  string|null  $id
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

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'tenantProfileId']);

        $tenantProfile = $this->api()->get("tenantProfile/{$id}")->json();

        return $this->fill($tenantProfile);
    }

    /**
     * Fetch the default Tenant Profile Info object based.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
     *
     * @param  bool  $full
     * @return self
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

        return $this->fill($tenantProfile);
    }

    /**
     * Fetch the Tenant Profile Info object based on the provided Tenant Profile Id.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
     *
     * @group SYS_ADMIN
     *
     * @param  string|null  $id
     * @return self
     *
     * @throws \Throwable
     *
     * @author Sabiee
     */
    public function getTenantProfileInfoById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'tenantProfileId']);

        $tenantProfile = $this->api()->get("tenantProfileInfo/{$id}")->json();

        return $this->fill($tenantProfile);
    }

    /**
     * Returns a page of tenant profile info objects registered in the platform.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantProfileInfos(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumTenantProfileSortProperty::class);

        $response = $this->api()->get('tenantProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Makes specified tenant profile to be default.
     * Referencing non-existing tenant profile Id will cause an error.
     *
     * @param  string|null  $id
     * @param  bool  $sync
     * @return TenantProfile
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function setDefaultTenantProfile(string $id = null, bool $sync = false): TenantProfile
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'tenantProfileId']);

        $tenantProfile = $this->api()->post("tenantProfile/{$id}/default", $this->attributes)->json();
        if ($sync) {
            return $this->getTenantProfileById($id);
        }

        return $this->fill($tenantProfile);

    }

    /**
     * Returns a page of tenant profiles registered in the platform.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @return LengthAwarePaginator
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function getTenantProfiles(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumTenantProfileSortProperty::class);

        $response = $this->api()->get('tenantProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get multiple tenantProfile by ids
     *
     * @param  array  $ids
     * @return TenantProfile[]
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN
     */
    public function getTenantProfilesByIds(array $ids): array
    {
        foreach ($ids as $id) {
            Thingsboard::validation(! Str::isUuid($id), 'array_of', ['attribute' => 'ids', 'struct' => 'uuid']);
        }

        $tenantProfiles = $this->api()->get('/tenantProfiles', ['ids' => implode(',', $ids)])->json();

        return array_map(fn ($tenantProfile) => new TenantProfile($tenantProfile), $tenantProfiles);
    }
}
