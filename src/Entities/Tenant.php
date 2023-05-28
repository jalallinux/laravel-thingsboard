<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTenantSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id;
 * @property Id $tenantProfileId
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
        'id' => CastId::class,
        'tenantProfileId' => CastId::class,
        'createdTime' => 'timestamp',
        'additionalInfo' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::TENANT();
    }

    /**
     * Returns a page of tenant info objects registered in the platform.
     * The Tenant Info object extends regular Tenant object and includes Tenant Profile name.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenants(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumTenantSortProperty::class);

        $response = $this->api()->get('tenantInfos', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
