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
     * @return TenantProfile
     * @author Sabiee
     */
    public function saveTenantProfile(): TenantProfile
    {
        $payload = array_merge($this->getAttributes(), [
            'name' => $this->forceAttribute('name'),
        ]);
        $this->forceAttribute('name');
        $tenantProfile = $this->api()->post("tenantProfile", $payload)->json();
        return tap($this, fn() => $this->fill($tenantProfile));
    }

    /**
     * Deletes the tenant profile.
     * Referencing non-existing tenant profile Id will cause an error.
     * Referencing profile that is used by the tenants will cause an error.
     *
     * @return bool
     * @throws \Throwable
     * @group SYS_ADMIN
     *
     * @author Sabiee
     */
    public function deleteTenantProfile(): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("tenantProfile/{$id}")->successful();
    }

    /**
     * Fetch the Tenant Profile object based on the provided Tenant Profile Id.
     *
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
            !Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $tenantProfile = $this->api()->get("tenantProfile/{$id}")->json();

        return tap($this, fn() => $this->fill($tenantProfile));
    }

    /**
     * Fetch the default Tenant Profile Info object based.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
     *
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

        return tap($this, fn() => $this->fill($tenantProfile));
    }

    /**
     * Returns a page of tenant profile info objects registered in the platform.
     * Tenant Profile Info is a lightweight object that contains only id and name of the profile.
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
    public function getTenantProfileInfos(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumTenantProfileSortProperty::class);

        $response = $this->api()->get('tenantProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
