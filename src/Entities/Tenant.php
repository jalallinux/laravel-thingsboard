<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\TenantSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Interfaces\ThingsboardEntityId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardEntityId $id;
 * @property ThingsboardEntityId $tenantProfileId
 * @property \DateTime $createdTime
 * @property string $title
 * @property string $name
 * @property string $region
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $address
 * @property string $address2
 * @property string $zip
 * @property string $phone
 * @property string $email
 * @property array $additionalInfo
 * @property string $tenantProfileName
 */
class Tenant extends Tntity
{
    protected $fillable = [
        'id',
        'tenantProfileId',
        'createdTime',
        'title',
        'name',
        'region',
        'country',
        'state',
        'city',
        'address',
        'address2',
        'zip',
        'phone',
        'email',
        'additionalInfo',
        'tenantProfileName',
    ];

    protected $casts = [
        'id' => 'id',
        'tenantProfileId' => 'id',
        'createdTime' => 'timestamp',
        'additionalInfo' => 'array',
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return ThingsboardEntityType::TENANT();
    }

    /**
     * Get Tenants Info
     * @param ThingsboardPaginationArguments $paginationArguments
     * @return ThingsboardPaginatedResponse
     * @author JalalLinuX
     * @group SYS_ADMIN
     */
    public function getTenants(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $paginationArguments->validateSortProperty(TenantSortProperty::class);

        $response = $this->api(true)->get('tenantInfos', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
