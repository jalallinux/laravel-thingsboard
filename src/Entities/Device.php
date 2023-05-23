<?php

namespace JalalLinuX\Thingsboard\Entities;

use DateTime;
use JalalLinuX\Thingsboard\PaginatedResponse;
use JalalLinuX\Thingsboard\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $id
 * @property DateTime $createdTime
 * @property string $type
 * @property string $name
 * @property string $label
 * @property array $additionalInfo
 * @property array $customerId
 * @property array $deviceProfileId
 * @property array $deviceData
 * @property string $searchText
 * @property array $tenantId
 * @property array $firmwareId
 * @property array $softwareId
 * @property array $externalId
 */
class Device extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'type',
        'name',
        'label',
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
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN, CUSTOMER_USER
     */
    public function getById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id');

        throw_if(
            ! uuid_is_valid($id),
            $this->exception('method "id" argument must be a valid uuid.'),
        );

        $device = $this->api(true)->get("/device/{$id}")->json();

        return tap($this, fn () => $this->fill($device));
    }

    public function list(PaginationArguments $paginationArguments, string $customerId = null, string $deviceProfileId = null, bool $active = null): PaginatedResponse
    {
        $customerId = $customerId ?? @$this->forceAttribute('customerId')['id'];

        throw_if(
            ! uuid_is_valid($customerId),
            $this->exception('method "customerId" argument must be a valid uuid.'),
        );

        $response = $this->api(true)->get("customer/{$customerId}/deviceInfos", $paginationArguments->queryParams([
            'active' => $active, 'deviceProfileId' => $deviceProfileId,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
