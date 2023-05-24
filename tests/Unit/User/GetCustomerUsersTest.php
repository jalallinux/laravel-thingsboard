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
        $user = $this->thingsboardUser(ThingsboardUserRole::TENANT_ADMIN());

        $customerUsers = thingsboard()->user()->withUser($user)->getCustomerUsers(
            ThingsboardPaginationArguments::make(textSearch: 'A'),
            'a504e040-f7a8-11ed-bccf-f7083aaa7dff'
        );

        $customerUsers->data()->each(fn ($device) => $this->assertInstanceOf(User::class, $device));
        self::assertStringContainsString('A', $customerUsers->data()->first()->name);
    }
}
