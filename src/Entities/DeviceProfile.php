<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id;
 * @property \DateTime $createdTime;
 * @property bool $default;
 * @property string $name;
 * @property string $type;
 * @property string $description;
 * @property string $image;
 * @property string $defaultQueueName;
 * @property string $provisionDeviceKey;
 * @property string $transportType;
 * @property string $provisionType;
 * @property array $profileData;
 * @property Id $tenantId;
 * @property Id $defaultDashboardId;
 * @property Id $defaultRuleChainId;
 * @property Id $firmwareId;
 * @property Id $softwareId;
 * @property Id $defaultEdgeRuleChainId;
 */
class DeviceProfile extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'default',
        'name',
        'type',
        'description',
        'image',
        'defaultQueueName',
        'provisionDeviceKey',
        'transportType',
        'provisionType',
        'profileData',
        'tenantId',
        'defaultDashboardId',
        'defaultRuleChainId',
        'firmwareId',
        'softwareId',
        'defaultEdgeRuleChainId',
    ];

    protected $casts = [
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'additionalInfo' => 'array',
        'customerId' => CastId::class,
        'deviceProfileId' => CastId::class,
        'profileData' => 'array',
        'tenantId' => CastId::class,
        'firmwareId' => CastId::class,
        'softwareId' => CastId::class,
        'externalId' => CastId::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::DEVICE_PROFILE();
    }

    /**
     * Returns a page of devices profile objects owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     *
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getDeviceProfiles(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumDeviceProfileSortProperty::class);

        $response = $this->api()->get('deviceProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Fetch the Device Profile object based on the provided Device Profile ID.
     * The server checks that the device profile is owned by the same tenant.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getDeviceProfileById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            !Str::isUuid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        $deviceProfile = $this->api()->get("deviceProfile/{$id}")->json();

        return tap($this, fn() => $this->fill($deviceProfile));
    }

    /**
     * Fetch the Default Device Profile Info object.
     * Device Profile Info is a lightweight object that includes main information about Device Profile excluding the heavyweight configuration object.
     *
     * @author JalallinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDefaultDeviceProfileInfo(bool $full = false): static
    {
        $deviceProfile = $this->api()->get('deviceProfileInfo/default')->json();

        if ($full) {
            return $this->getDeviceProfileById($deviceProfile['id']['id']);
        }

        return tap($this, fn() => $this->fill($deviceProfile));
    }

    /**
     * Create or update the Device Profile.
     * When creating device profile, platform generates device profile id as time-based UUID.
     * The newly created device profile id will be present in the response.
     * Specify existing device profile id to update the device profile.
     * Referencing non-existing device profile Id will cause 'Not Found' error.
     * Device profile name is unique in the scope of tenant.
     * Only one 'default' device profile may exist in scope of tenant.
     *
     * @group TENANT_ADMIN
     *
     * @return DeviceProfile
     * @author Sabiee
     */
    public function saveDeviceProfile()
    {
        $payload = array_merge($this->getAttributes(), [
            'name' => $this->forceAttribute('name'),
            'type' => 'DEFAULT',
            'provisionType' => $this->forceAttribute('provisionType'),
            'transportType' => $this->forceAttribute('transportType'),
        ]);

        if (is_null($this->get('profileData.configuration.type'))) {
            $payload['profileData']['configuration']['type'] = 'DEFAULT';
        }

        if (is_null($this->get('profileData.provisionConfiguration.type'))) {
            $payload['profileData']['provisionConfiguration']['type'] = 'DISABLED';
        }

        if (is_null($this->get('profileData.transportConfiguration.type'))) {
            $payload['profileData']['transportConfiguration']['type'] = 'DEFAULT';
        }

        $deviceProfile = $this->api()->post('deviceProfile', $payload)->json();

        return tap($this, fn() => $this->fill($deviceProfile));
    }

    /**
     * Deletes the device profile.
     * Referencing non-existing device profile Id will cause an error.
     * Can't delete the device profile if it is referenced by existing devices.
     *
     * @group TENANT_ADMIN
     *
     * @param string|null $id
     * @return bool
     * @throws \Throwable
     * @author Sabiee
     */
    public function deleteDeviceProfile(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            !Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("deviceProfile/{$id}")->successful();
    }
}
