<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\IdCast;
use JalalLinuX\Thingsboard\Enums\CustomerSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\infrastructure\Id;
use JalalLinuX\Thingsboard\infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id;
 * @property \DateTime $createdTime;
 * @property Id $tenantId;
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
        'id' => IdCast::class,
        'createdTime' => 'timestamp',
        'tenantId' => IdCast::class,
        'additionalInfo' => 'array',
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return ThingsboardEntityType::CUSTOMER();
    }

    /**
     * Returns a page of customers owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getCustomers(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(CustomerSortProperty::class);

        $response = $this->api()->get('customers', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
