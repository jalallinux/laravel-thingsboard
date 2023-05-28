<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Auth;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\Token;
use JalalLinuX\Thingsboard\Tests\TestCase;

class ActivateUserTest extends TestCase
{
    public function testSuccess()
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

        $activationLink = thingsboard($tenantUser)->user()->getActivationLink($newUser->id->id);
        $queryParams = collect(explode('&', parse_url($activationLink, PHP_URL_QUERY)))
            ->mapWithKeys(fn ($param) => [explode('=', $param)[0] => explode('=', $param)[1]]);
        $tokens = thingsboard()->auth()->activateUser($queryParams['activateToken'], '123456');

        $newUser->deleteUser();
        $this->assertInstanceOf(Token::class, $tokens);
    }
}
