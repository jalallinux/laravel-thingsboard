<?php

namespace JalalLinuX\Thingsboard\Entities;

use DateTime;
use Illuminate\Support\HigherOrderTapProxy;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumDeviceSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumThingsboardEntityType;
use JalalLinuX\Thingsboard\infrastructure\Id;
use JalalLinuX\Thingsboard\infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property DateTime $createdTime
 * @property string $type
 * @property string $name
 * @property string $label
 * @property bool $active
 * @property array $additionalInfo
 * @property array $deviceData
 * @property string $searchText
 * @property Id $customerId
 * @property Id $deviceProfileId
 * @property Id $tenantId
 * @property Id $firmwareId
 * @property Id $softwareId
 * @property Id $externalId
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
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'active' => 'bool',
        'additionalInfo' => 'array',
        'customerId' => CastId::class,
        'deviceProfileId' => CastId::class,
        'deviceData' => 'array',
        'tenantId' => CastId::class,
        'firmwareId' => CastId::class,
        'softwareId' => CastId::class,
        'externalId' => CastId::class,
    ];

    public function entityType(): ?EnumThingsboardEntityType
    {
        return EnumThingsboardEntityType::DEVICE();
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
    public function getTenantDeviceInfos(PaginationArguments $paginationArguments, string $deviceProfileId = null, bool $active = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumDeviceSortProperty::class);

        $response = $this->api()->get('tenant/deviceInfos', $paginationArguments->queryParams([
            'active' => $active ?? $this->active, 'deviceProfileId' => $deviceProfileId ?? @$this->deviceProfileId->id,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Creates assignment of the device to customer.
     * Customer will be able to query device afterward.
     * @param string $customerId
     * @param string|null $id
     * @return Device
     * @throws \Throwable
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function assignDeviceToCustomer(string $customerId, string $id = null):self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id) || ! Str::isUuid($customerId),
            $this->exception('method "id", "customerId" argument must be a valid uuid.'),
        );

        $device = $this->api()->post("customer/{$customerId}/device/{$id}")->json();
        return tap($this, fn() => $this->fill($device));
    }

    /**
     * Clears assignment of the device to customer.
     * Customer will not be able to query device afterward.
     * @param string|null $id
     * @return bool
     * @throws \Throwable
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function unAssignDeviceFromCustomer(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api()->delete("customer/device/{$id}")->successful();
    }
}
