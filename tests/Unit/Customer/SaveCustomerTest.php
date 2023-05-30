<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Customer;

use JalalLinuX\Thingsboard\Entities\Customer;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveCustomerTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCreateCustomerSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'title' => $this->faker->sentence,
            'country' => $this->faker->country,
            'state' => $this->faker->word,
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'address2' => $this->faker->address,
            'zip' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'additionalInfo' => [],
        ];
        $newCustomer = thingsboard($tenantUser)->customer($attributes)->saveCustomer();

        $this->assertInstanceOf(Customer::class, $newCustomer);
        $this->assertInstanceOf(Id::class, $newCustomer->id);
        $this->assertInstanceOf(Id::class, $newCustomer->tenantId);
        $this->assertEquals($attributes['country'], $newCustomer->country);
        $this->assertEquals($attributes['address2'], $newCustomer->address2);
        $this->assertEquals($attributes['email'], $newCustomer->email);

        $result = thingsboard($tenantUser)->customer()->deleteCustomer($newCustomer->id->id);
        $this->assertTrue($result);
    }

    public function testRequiredProperty()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $attributes = [
            'country' => $this->faker->country,
            'state' => $this->faker->word,
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'address2' => $this->faker->address,
            'zip' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'additionalInfo' => [],
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/title/');
        thingsboard($tenantUser)->customer($attributes)->saveCustomer();
    }

    public function testExistsTitle()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'title' => thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->data()->first()->title,
            'country' => $this->faker->country,
            'state' => $this->faker->word,
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'address2' => $this->faker->address,
            'zip' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'additionalInfo' => [],
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/exists!/');
        thingsboard($tenantUser)->customer($attributes)->saveCustomer();
    }
}
