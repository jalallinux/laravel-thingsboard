<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveUserTest extends TestCase
{
    public function testCreateTenantSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantId = thingsboard($adminUser)->tenant()->getTenants(PaginationArguments::make())->collect()->first()->id;
        $attributes = [
            'tenantId' => $tenantId,
            'email' => $this->faker->unique()->safeEmail,
            'authority' => EnumAuthority::TENANT_ADMIN(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $newUser = thingsboard($adminUser)->user($attributes)->saveUser();

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertInstanceOf(Id::class, $newUser->id);
        $this->assertInstanceOf(EnumAuthority::class, $newUser->authority);

        $result = thingsboard($adminUser)->user()->deleteUser($newUser->id->id);
        $this->assertTrue($result);
    }

    public function testCreateCustomerSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->first()->id;
        $attributes = [
            'customerId' => $customerId,
            'email' => $this->faker->unique()->safeEmail,
            'authority' => EnumAuthority::CUSTOMER_USER(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $newUser = thingsboard($tenantUser)->user($attributes)->saveUser();

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertInstanceOf(Id::class, $newUser->id);

        $result = $newUser->deleteUser();
        $this->assertTrue($result);
    }

    public function testRequiredProperty()
    {
        $exceptKey = $this->faker->randomElement(['email', 'authority']);
        $authority = $this->faker->randomElement([EnumAuthority::SYS_ADMIN(), EnumAuthority::TENANT_ADMIN()]);
        $user = $this->thingsboardUser($authority);

        switch ($authority) {
            case EnumAuthority::SYS_ADMIN():
                $tenantId = thingsboard($user)->tenant()->getTenants(PaginationArguments::make())->collect()->first()->id;
                $attributes = [
                    'tenantId' => $tenantId,
                    'email' => $this->faker->unique()->safeEmail,
                    'authority' => EnumAuthority::TENANT_ADMIN(),
                    'firstName' => $this->faker->firstName,
                    'lastName' => $this->faker->lastName,
                    'phone' => $this->faker->e164PhoneNumber,
                    'additionalInfo' => [],
                ];
                $this->expectExceptionCode(500);
                $this->expectExceptionMessageMatches("/{$exceptKey}/");
                thingsboard($user)->user(Arr::except($attributes, $exceptKey))->saveUser();
                break;
            case EnumAuthority::TENANT_ADMIN():
                $customerId = thingsboard($user)->customer()->getCustomers(PaginationArguments::make())->collect()->first()->id;
                $attributes = [
                    'customerId' => $customerId,
                    'email' => $this->faker->unique()->safeEmail,
                    'authority' => EnumAuthority::CUSTOMER_USER(),
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
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->first()->id;
        $attributes = [
            'customerId' => $customerId,
            'email' => $this->thingsboardUser(EnumAuthority::CUSTOMER_USER())->getThingsboardEmailAttribute(),
            'authority' => EnumAuthority::CUSTOMER_USER(),
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
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'customerId' => new Id($this->faker->uuid, EnumEntityType::CUSTOMER()),
            'email' => $this->faker->unique()->safeEmail,
            'authority' => EnumAuthority::CUSTOMER_USER(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->e164PhoneNumber,
            'additionalInfo' => [],
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/non-existent customer/');
        thingsboard($tenantUser)->user($attributes)->saveUser();
    }
}
