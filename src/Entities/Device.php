<?php

namespace JalalLinuX\Thingsboard\Entities;

use DateTime;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumDeviceSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\infrastructure\DeviceCredentials;
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

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::DEVICE();
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
     * Fetch the Device Info object based on the provided Device IdD.
     * If the user has the authority of 'Tenant Administrator', the server checks that the device is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the device is assigned to the same customer.
     * Device Info is an extension of the default Device object that contains information about the assigned customer name and device profile name.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDeviceInfoById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $device = $this->api()->get("/device/info/{$id}")->json();

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
    public function getTenantDeviceInfos(PaginationArguments $paginationArguments, string $deviceProfileId = null, bool $active = null, string $type = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumDeviceSortProperty::class);

        $response = $this->api()->get('tenant/deviceInfos', $paginationArguments->queryParams([
            'active' => $active ?? @$this->active, 'type' => $type ?? @$this->type,
            'deviceProfileId' => $deviceProfileId ?? @$this->deviceProfileId->id,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of devices info objects assigned to customer.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * Device Info is an extension of the default Device object that contains information about the assigned customer name and device profile name.
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getCustomerDeviceInfos(PaginationArguments $paginationArguments, string $customerId = null, string $deviceProfileId = null, bool $active = null, string $type = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumDeviceSortProperty::class);

        $response = $this->api()->get("customer/{$customerId}/deviceInfos", $paginationArguments->queryParams([
            'active' => $active ?? @$this->active, 'type' => $type ?? @$this->type,
            'customerId' => $customerId ?? $this->forceAttribute('customerId')->id,
            'deviceProfileId' => $deviceProfileId ?? @$this->deviceProfileId->id,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Creates assignment of the device to customer.
     * Customer will be able to query device afterward.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function assignDeviceToCustomer(string $customerId, string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id) || ! Str::isUuid($customerId),
            $this->exception('method "id", "customerId" argument must be a valid uuid.'),
        );

        $device = $this->api()->post("customer/{$customerId}/device/{$id}")->json();

        return tap($this, fn () => $this->fill($device));
    }

    /**
     * Clears assignment of the device to customer.
     * Customer will not be able to query device afterward.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function unAssignDeviceFromCustomer(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("customer/device/{$id}")->successful();
    }

    /**
     * Deletes the device, it's credentials and all the relations (from and to the device).
     * Referencing non-existing device Id will cause an error.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function deleteDevice(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("device/{$id}")->successful();
    }

    /**
     * Create or update the Device.
     * When creating device, platform generates Device ID as time-based UUID.
     * Device credentials are also generated if not provided in the 'accessToken' request parameter.
     * The newly created device id will be present in the response.
     * Specify existing Device id to update the device.
     * Referencing non-existing device ID will cause 'Not Found' error.
     * Device name is unique in the scope of tenant.
     * Use unique identifiers like MAC or IMEI for the device names and non-unique 'label' field for user-friendly visualization purposes.
     * Remove 'id', 'tenantId' and optionally 'customerId' from the request body example (below) to create new Device entity.
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function saveDevice(string $accessToken = null, string $deviceProfileId = null): self
    {
        $deviceProfileId = $deviceProfileId ?? $this->deviceProfileId->id ?? DeviceProfile::instance()->withUser($this->_thingsboardUser)->getDefaultDeviceProfileInfo()->id->id;

        $payload = array_merge($this->getAttributes(), [
            'name' => $this->forceAttribute('name'),
            'deviceProfileId' => new Id($deviceProfileId, EnumEntityType::DEVICE_PROFILE()),
        ]);

        $device = $this->api()->post('device'.(! is_null($accessToken) ? "?accessToken={$accessToken}" : ''), $payload)->json();

        return tap($this, fn () => $this->fill($device));
    }

    /**
     * If during device creation there wasn't specified any credentials, platform generates random 'ACCESS_TOKEN' credentials.
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDeviceCredentialsByDeviceId(string $id = null): DeviceCredentials
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        return new DeviceCredentials($this->api()->get("device/{$id}/credentials")->json());
    }

    /**
     * During device creation, platform generates random 'ACCESS_TOKEN' credentials.
     * Use this method to update the device credentials.
     * First use 'getDeviceCredentialsByDeviceId' to get the credentials id and value.
     * Then use current method to update the credentials type and value.
     * It is not possible to create multiple device credentials for the same device.
     * The structure of device credentials id and value is simple for the 'ACCESS_TOKEN' but is much more complex for the 'MQTT_BASIC' or 'LWM2M_CREDENTIALS'.
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function updateDeviceCredentials(DeviceCredentials $credentials): DeviceCredentials
    {
        $newCredentials = $this->api()->post('device/credentials', $credentials->toArray())->json();

        return new DeviceCredentials($newCredentials);
    }
}
