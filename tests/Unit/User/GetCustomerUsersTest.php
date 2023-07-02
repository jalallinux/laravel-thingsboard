<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumUserSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerUsersTest extends TestCase
{
    public function testTextSearch()
    {
        $customerLetter = $this->faker->randomElement(['A', 'B', 'C']);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $customerId = thingsboard()->customer()->withUser($user)->getCustomers(
            PaginationArguments::make(textSearch: "Customer {$customerLetter}")
        )->collect()->first()->id->id;
        $customerUsers = thingsboard()->user()->withUser($user)->getCustomerUsers(
            PaginationArguments::make(textSearch: "customer{$customerLetter}"), $customerId
        );

        $customerUsers->collect()->each(fn ($device) => $this->assertInstanceOf(User::class, $device));
        self::assertStringContainsString($customerLetter, $customerUsers->collect()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumUserSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard()->customer()->withUser($user)->getCustomers(
            PaginationArguments::make()
        )->collect()->first()->id->id;

        $devices = thingsboard()->user(['customerId' => new Id($customerId, EnumEntityType::CUSTOMER())])->withUser($user)->getCustomerUsers($pagination);

        $this->assertEquals($pagination->page, $devices->currentPage());
        $this->assertEquals($pagination->pageSize, $devices->perPage());
        $this->assertEquals($pagination->sortOrder, $devices->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $devices->getOptions()['sortProperty']);
    }
}
