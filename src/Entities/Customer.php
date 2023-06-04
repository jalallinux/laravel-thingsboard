<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumCustomerSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
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
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'tenantId' => CastId::class,
        'additionalInfo' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::CUSTOMER();
    }

    /**
     * Returns a page of customers owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @return PaginatedResponse
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getCustomers(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumCustomerSortProperty::class);

        $response = $this->api()->get('customers', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Creates or Updates the Customer.
     * When creating customer, platform generates Customer Id as time-based UUID.
     * The newly created Customer Id will be present in the response. Specify existing Customer Id to update the Customer.
     * Referencing non-existing Customer Id will cause 'Not Found' error.Remove 'id', 'tenantId' from the request body example (below) to create new Customer entity.
     *
     * @return Customer
     *
     * @author Sabiee
     *
     * @group  TENANT_ADMIN
     */
    public function saveCustomer(): static
    {
        $payload = array_merge($this->attributes, [
            'title' => $this->forceAttribute('title'),
        ]);

        $customer = $this->api()->post('customer', $payload)->json();

        return $this->fill($customer);
    }

    /**
     * Get the Customer object based on the provided Customer Id.
     * If the user has the authority of 'Tenant Administrator', the server checks that the customer is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the user belongs to the customer.
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getCustomerById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'customerId']);

        $customer = $this->api()->get("customer/{$id}")->json();

        return $this->fill($customer);
    }

    /**
     * Deletes the Customer and all customer Users.
     * All assigned Dashboards, Assets, Devices, etc. will be unassigned but not deleted.
     * Referencing non-existing Customer Id will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @throws \Throwable
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function deleteCustomer(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'customerId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("customer/{$id}")->successful();
    }
}
