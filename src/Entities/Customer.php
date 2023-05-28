<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumCustomerSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
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
     * @group  TENANT_ADMIN
     *
     * @author Sabiee
     */
    public function saveCustomer(): self
    {
        $payload = array_merge($this->getAttributes(), [
            'title' => $this->forceAttribute('title'),
        ]);

        $customer = $this->api()->post('customer', $payload)->json();

        return tap($this, fn () => $this->fill($customer));
    }

    /**
     * Deletes the Customer and all customer Users.
     * All assigned Dashboards, Assets, Devices, etc. will be unassigned but not deleted.
     * Referencing non-existing Customer Id will cause an error.
     *
     * @group TENANT_ADMIN
     *
     * @throws \Throwable
     *
     * @author Sabiee
     */
    public function deleteCustomer(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        return $this->api(handleException: self::config('rest.exception.throw_bool_methods'))->delete("customer/{$id}")->successful();
    }
}
