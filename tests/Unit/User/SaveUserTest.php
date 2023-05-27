<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class SaveUserTest extends TestCase
{
    public function testCreateTenantSuccess()
    {
        $adminUser = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());
        $tenantId = thingsboard($adminUser)->tenant()->getTenants(ThingsboardPaginationArguments::make())->data()->first()->id;
        $attributes = [
            'tenantId' => $tenantId,
            'email' => $this->faker->unique()->safeEmail,
            'authority' => ThingsboardAuthority::TENANT_ADMIN(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $newUser = thingsboard($adminUser)->user($attributes)->saveUser();

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertInstanceOf(ThingsboardId::class, $newUser->id);

        $result = thingsboard($adminUser)->user()->deleteUser($newUser->id->id);
        $this->assertTrue($result);
    }

    public function testCreateCustomerSuccess()
    {
        $tenantUser = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(ThingsboardPaginationArguments::make())->data()->first()->id;
        $attributes = [
            'customerId' => $customerId,
            'email' => $this->faker->unique()->safeEmail,
            'authority' => ThingsboardAuthority::CUSTOMER_USER(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $newUser = thingsboard($tenantUser)->user($attributes)->saveUser();

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertInstanceOf(ThingsboardId::class, $newUser->id);

        $result = thingsboard($tenantUser)->user()->deleteUser($newUser->id->id);
        $this->assertTrue($result);
    }
}
