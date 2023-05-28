<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Customer;

use JalalLinuX\Thingsboard\Entities\Customer;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteCustomerTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customer = thingsboard($tenantUser)->customer(['title' => $this->faker->sentence(3)])->saveCustomer();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertInstanceOf(Id::class, $customer->id);

        $result = $customer->deleteCustomer();
        self::assertTrue($result);
    }

    /**
     * @throws \Throwable
     */
    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->customer()->deleteCustomer($uuid);
    }
}
