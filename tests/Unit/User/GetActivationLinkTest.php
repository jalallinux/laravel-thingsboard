<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use Illuminate\Support\Facades\Http;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetActivationLinkTest extends TestCase
{
    public function testCorrectUuid()
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
        $activationLink = thingsboard($tenantUser)->user()->getActivationLink($newUser->id->id);
        self::assertTrue(Http::get($activationLink)->successful());
        $newUser->deleteUser();
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->user()->getActivationLink($uuid);
    }
}
