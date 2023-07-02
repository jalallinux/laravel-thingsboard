<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumUserSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetUsersTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser($this->faker->randomElement([EnumAuthority::TENANT_ADMIN(), EnumAuthority::CUSTOMER_USER()]));

        $users = thingsboard($user)->user()->getUsers(
            PaginationArguments::make()
        );

        $users->collect()->each(fn ($user) => $this->assertInstanceOf(User::class, $user));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumUserSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $devices = thingsboard()->user()->withUser($user)->getUsers($pagination);

        $this->assertEquals($pagination->page, $devices->currentPage());
        $this->assertEquals($pagination->pageSize, $devices->perPage());
        $this->assertEquals($pagination->sortOrder, $devices->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $devices->getOptions()['sortProperty']);
    }
}
