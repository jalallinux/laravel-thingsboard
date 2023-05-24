<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetCustomerUsersTest extends TestCase
{
    public function testTextSearch()
    {
        $customerLetter = fake()->randomElement(['A', 'B', 'C']);
        $user = $this->thingsboardUser(ThingsboardUserRole::TENANT_ADMIN());

        $customerId = thingsboard()->customer()->withUser($user)->getCustomers(
            ThingsboardPaginationArguments::make(textSearch: $customerLetter)
        )->data()->first()->id->id;
        $customerUsers = thingsboard()->user()->withUser($user)->getCustomerUsers(
            ThingsboardPaginationArguments::make(textSearch: $customerLetter), $customerId
        );

        $customerUsers->data()->each(fn ($device) => $this->assertInstanceOf(User::class, $device));
        self::assertStringContainsString($customerLetter, $customerUsers->data()->first()->name);
    }
}
