<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\CustomerSortProperty;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $id;
 * @property \DateTime $createdTime;
 * @property array $tenantId;
 * @property string $title;
 * @property string $name;
 * @property string $country;
 * @property string $state;
 * @property string $city;
 * @property string $address;
 * @property string $address2;
 * @property string $zip;
 * @property string $phone;
 * @property string $email;
 * @property array $additionalInfo;
 */
class Customer extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'title',
        'name',
        'country',
        'state',
        'city',
        'address',
        'address2',
        'zip',
        'phone',
        'email',
        'additionalInfo',
    ];

    protected $casts = [
        'id' => 'array',
        'createdTime' => 'timestamp',
        'tenantId' => 'array',
        'additionalInfo' => 'array',
    ];

    /**
     * Get Tenant Customers
     * @param ThingsboardPaginationArguments $paginationArguments
     * @return ThingsboardPaginatedResponse
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function getCustomers(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $paginationArguments->validateSortProperty(CustomerSortProperty::class);

        $response = $this->api(true)->get("customers", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
