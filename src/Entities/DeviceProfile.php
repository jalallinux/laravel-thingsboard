<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Tntity;

class DeviceProfile extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'name',
        'type',
        'image',
        'transportType',
        'provisionType',
        'profileData',
        'description',
        'searchText',
        'isDefault',
        'tenantId',
        'firmwareId',
        'softwareId',
        'defaultRuleChainId',
        'defaultDashboardId',
        'defaultQueueName',
        'provisionDeviceKey',
        'externalId',
    ];

    protected $casts = [
        'id' => 'array',
        'createdTime' => 'timestamp',
        'type' => 'string',
        'name' => 'string',
        'label' => 'string',
        'additionalInfo' => 'array',
        'customerId' => 'array',
        'deviceProfileId' => 'array',
        'deviceData' => 'array',
        'tenantId' => 'array',
        'firmwareId' => 'array',
        'softwareId' => 'array',
        'externalId' => 'array',
    ];

    /**
     * Get Device Profile
     * @param string|null $id
     * @return self
     * @throws \Throwable
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function getDeviceProfileById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id');

        throw_if(
            ! uuid_is_valid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        $device = $this->api(true)->get("/deviceProfile/{$id}")->json();

        return tap($this, fn () => $this->fill($device));
    }
}
