<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Customer;

use JalalLinuX\Thingsboard\Entities\Customer;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerByIdTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $customer = thingsboard()->customer()->withUser($tenantUser)->getCustomers(PaginationArguments::make())->data()->first();
        $customerId = $customer->id->id;
        $customerTenantId = $customer->tenantId->id;

        $customer = thingsboard($tenantUser)->customer()->getCustomerById($customerId);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customerId, $customer->id->id);
        $this->assertEquals($customerTenantId, $customer->tenantId->id);
    }

    /**
     * Get the Customer object based on the provided Customer Id.
     * If the user has the authority of 'Tenant Administrator', the server checks that the customer is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the user belongs to the customer.
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     *
     * @throws \Throwable
     *
     * @author Sabiee
     */
    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->customer()->getCustomerById($uuid);
    }
}
