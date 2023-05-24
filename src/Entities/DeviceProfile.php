<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\DeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardEntityId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardEntityId $id;
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
 * @property ThingsboardEntityId $tenantId;
 * @property ThingsboardEntityId $defaultDashboardId;
 * @property ThingsboardEntityId $defaultRuleChainId;
 * @property ThingsboardEntityId $firmwareId;
 * @property ThingsboardEntityId $softwareId;
 * @property ThingsboardEntityId $defaultEdgeRuleChainId;
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
        'id' => 'id',
        'createdTime' => 'timestamp',
        'additionalInfo' => 'array',
        'customerId' => 'id',
        'deviceProfileId' => 'id',
        'deviceData' => 'array',
        'tenantId' => 'id',
        'firmwareId' => 'id',
        'softwareId' => 'id',
        'externalId' => 'id',
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return ThingsboardEntityType::DEVICE_PROFILE();
    }

    public function getDeviceProfiles(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $paginationArguments->validateSortProperty(DeviceProfileSortProperty::class);

        $response = $this->api(true)->get('deviceProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get Device Profile
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getDeviceProfileById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! uuid_is_valid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        $device = $this->api(true)->get("/deviceProfile/{$id}")->json();

        return tap($this, fn () => $this->fill($device));
    }
}
