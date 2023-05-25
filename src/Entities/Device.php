<?php

namespace JalalLinuX\Thingsboard\Entities;

use DateTime;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\DeviceSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardEntityId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardEntityId $id
 * @property DateTime $createdTime
 * @property string $type
 * @property string $name
 * @property string $label
 * @property bool $active
 * @property array $additionalInfo
 * @property array $deviceData
 * @property string $searchText
 * @property ThingsboardEntityId $customerId
 * @property ThingsboardEntityId $deviceProfileId
 * @property ThingsboardEntityId $tenantId
 * @property ThingsboardEntityId $firmwareId
 * @property ThingsboardEntityId $softwareId
 * @property ThingsboardEntityId $externalId
 */
class Device extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'type',
        'name',
        'label',
        'active',
        'additionalInfo',
        'customerId',
        'deviceProfileId',
        'deviceData',
        'searchText',
        'tenantId',
        'firmwareId',
        'softwareId',
        'externalId',
    ];

    protected $casts = [
        'id' => 'id',
        'createdTime' => 'timestamp',
        'type' => 'string',
        'name' => 'string',
        'label' => 'string',
        'active' => 'bool',
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
        return ThingsboardEntityType::DEVICE();
    }

    /**
     * Get Device
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN, CUSTOMER_USER
     */
    public function getDeviceById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $device = $this->api(true)->get("/device/{$id}")->json();

        return tap($this, fn () => $this->fill($device));
    }

    /**
     * Get Tenant Device Infos
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getTenantDeviceInfos(ThingsboardPaginationArguments $paginationArguments, string $deviceProfileId = null, bool $active = null): ThingsboardPaginatedResponse
    {
        $paginationArguments->validateSortProperty(DeviceSortProperty::class);

        $response = $this->api(true)->get('tenant/deviceInfos', $paginationArguments->queryParams([
            'active' => $active ?? $this->active, 'deviceProfileId' => $deviceProfileId ?? @$this->deviceProfileId->id,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
