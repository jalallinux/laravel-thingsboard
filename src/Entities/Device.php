<?php

namespace JalalLinuX\Thingsboard\Entities;

use DateTime;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\IdCast;
use JalalLinuX\Thingsboard\Enums\DeviceSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardId $id
 * @property DateTime $createdTime
 * @property string $type
 * @property string $name
 * @property string $label
 * @property bool $active
 * @property array $additionalInfo
 * @property array $deviceData
 * @property string $searchText
 * @property ThingsboardId $customerId
 * @property ThingsboardId $deviceProfileId
 * @property ThingsboardId $tenantId
 * @property ThingsboardId $firmwareId
 * @property ThingsboardId $softwareId
 * @property ThingsboardId $externalId
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
        'id' => IdCast::class,
        'createdTime' => 'timestamp',
        'active' => 'bool',
        'additionalInfo' => 'array',
        'customerId' => IdCast::class,
        'deviceProfileId' => IdCast::class,
        'deviceData' => 'array',
        'tenantId' => IdCast::class,
        'firmwareId' => IdCast::class,
        'softwareId' => IdCast::class,
        'externalId' => IdCast::class,
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return ThingsboardEntityType::DEVICE();
    }

    /**
     * Fetch the Device object based on the provided Device ID.
     * If the user has the authority of 'TENANT_ADMIN', the server checks that the device is owned by the same tenant.
     * If the user has the authority of 'CUSTOMER_USER', the server checks that the device is assigned to the same customer.
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

        $device = $this->api()->get("/device/{$id}")->json();

        return tap($this, fn () => $this->fill($device));
    }

    /**
     * Returns a page of devices info objects owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * Device Info is an extension of the default Device object that contains information about the assigned customer name and device profile name.
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

        $response = $this->api()->get('tenant/deviceInfos', $paginationArguments->queryParams([
            'active' => $active ?? $this->active, 'deviceProfileId' => $deviceProfileId ?? @$this->deviceProfileId->id,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
