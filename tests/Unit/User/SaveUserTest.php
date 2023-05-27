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
        $user = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());
        $tenantId = thingsboard($user)->tenant()->getTenants(ThingsboardPaginationArguments::make())->data()->first()->id;
        $attributes = [
            'tenantId' => $tenantId,
            'email' => $this->faker->unique()->safeEmail,
            'authority' => ThingsboardAuthority::TENANT_ADMIN(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => []
        ];
        $newUser = thingsboard($user)->user($attributes)->saveUser();

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertInstanceOf(ThingsboardId::class, $newUser->id);
    }

    public function testCreateCustomerSuccess()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());
        $customerId = thingsboard($user)->customer()->getCustomers(ThingsboardPaginationArguments::make())->data()->first()->id;
        $attributes = [
            'customerId' => $customerId,
            'email' => $this->faker->unique()->safeEmail,
            'authority' => ThingsboardAuthority::CUSTOMER_USER(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => []
        ];
        $newUser = thingsboard($user)->user($attributes)->saveUser();

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertInstanceOf(ThingsboardId::class, $newUser->id);
    }
}
