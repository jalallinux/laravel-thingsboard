<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
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

    public function testRequiredProperty()
    {
        $exceptKey = $this->faker->randomElement(['email', 'authority']);
        $authority = $this->faker->randomElement([ThingsboardAuthority::SYS_ADMIN(), ThingsboardAuthority::TENANT_ADMIN()]);
        $user = $this->thingsboardUser($authority);

        switch ($authority) {
            case ThingsboardAuthority::SYS_ADMIN():
                $tenantId = thingsboard($user)->tenant()->getTenants(ThingsboardPaginationArguments::make())->data()->first()->id;
                $attributes = [
                    'tenantId' => $tenantId,
                    'email' => $this->faker->unique()->safeEmail,
                    'authority' => ThingsboardAuthority::TENANT_ADMIN(),
                    'firstName' => $this->faker->firstName,
                    'lastName' => $this->faker->lastName,
                    'phone' => $this->faker->e164PhoneNumber,
                    'additionalInfo' => [],
                ];
                $this->expectExceptionCode(500);
                $this->expectExceptionMessageMatches("/{$exceptKey}/");
                thingsboard($user)->user(Arr::except($attributes, $exceptKey))->saveUser();
                break;
            case ThingsboardAuthority::TENANT_ADMIN():
                $customerId = thingsboard($user)->customer()->getCustomers(ThingsboardPaginationArguments::make())->data()->first()->id;
                $attributes = [
                    'customerId' => $customerId,
                    'email' => $this->faker->unique()->safeEmail,
                    'authority' => ThingsboardAuthority::CUSTOMER_USER(),
                    'firstName' => $this->faker->firstName,
                    'lastName' => $this->faker->lastName,
                    'phone' => $this->faker->e164PhoneNumber,
                    'additionalInfo' => [],
                ];
                $this->expectExceptionCode(500);
                $this->expectExceptionMessageMatches("/{$exceptKey}/");
                thingsboard($user)->user(Arr::except($attributes, $exceptKey))->saveUser();
                break;
        }
    }

    public function testExistsEmail()
    {
        $tenantUser = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(ThingsboardPaginationArguments::make())->data()->first()->id;
        $attributes = [
            'customerId' => $customerId,
            'email' => $this->thingsboardUser(ThingsboardAuthority::CUSTOMER_USER())->getThingsboardEmailAttribute(),
            'authority' => ThingsboardAuthority::CUSTOMER_USER(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches("/{$attributes['email']}/");
        thingsboard($tenantUser)->user($attributes)->saveUser();
    }

    public function testNonExistsCustomerId()
    {
        $tenantUser = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());
        $attributes = [
            'customerId' => new ThingsboardId($this->faker->uuid, ThingsboardEntityType::CUSTOMER()),
            'email' => $this->faker->unique()->safeEmail,
            'authority' => ThingsboardAuthority::CUSTOMER_USER(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches("/non-existent customer/");
        thingsboard($tenantUser)->user($attributes)->saveUser();
    }
}
