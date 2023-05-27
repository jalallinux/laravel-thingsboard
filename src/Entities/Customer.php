<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\Id;
use JalalLinuX\Thingsboard\Enums\CustomerSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardId $id;
 * @property \DateTime $createdTime;
 * @property ThingsboardId $tenantId;
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
        'id' => Id::class,
        'createdTime' => 'timestamp',
        'tenantId' => Id::class,
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
    public function getCustomers(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $paginationArguments->validateSortProperty(CustomerSortProperty::class);

        $response = $this->api()->get('customers', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
