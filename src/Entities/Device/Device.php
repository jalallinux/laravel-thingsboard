<?php

namespace JalalLinuX\Tntity\Entities\Device;

use DateTime;
use JalalLinuX\Tntity\Entities\Tntity;
use JalalLinuX\Tntity\Traits\WithThingsboardAuthentication;

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
    use WithThingsboardAuthentication;

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

    public function getById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id');

        throw_if(
            ! uuid_is_valid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        return tap($this, fn () => $this->fill($this->api()->get("/device/{$id}")->json()));
    }
}
