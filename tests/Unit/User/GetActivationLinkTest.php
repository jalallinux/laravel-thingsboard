<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use Illuminate\Support\Facades\Http;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetActivationLinkTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->data()->first()->id;
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
        $this->assertInstanceOf(EnumAuthority::class, $newUser->authority);
        $activationLink = thingsboard($tenantUser)->user()->getActivationLink($newUser->id->id);
        $this->assertTrue(Http::get($activationLink)->successful());
        $newUser->deleteUser();
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->user()->getActivationLink($uuid);
    }
}
