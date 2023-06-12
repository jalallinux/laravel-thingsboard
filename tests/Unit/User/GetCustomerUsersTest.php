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
        )->data()->first()->id->id;
        $customerUsers = thingsboard()->user()->withUser($user)->getCustomerUsers(
            PaginationArguments::make(textSearch: "customer{$customerLetter}"), $customerId
        );

        $customerUsers->data()->each(fn ($device) => $this->assertInstanceOf(User::class, $device));
        self::assertStringContainsString($customerLetter, $customerUsers->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumUserSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard()->customer()->withUser($user)->getCustomers(
            PaginationArguments::make()
        )->data()->first()->id->id;

        $devices = thingsboard()->user(['customerId' => new Id($customerId, EnumEntityType::CUSTOMER())])->withUser($user)->getCustomerUsers($pagination);

        $this->assertEquals($pagination->page, $devices->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $devices->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $devices->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $devices->paginator()->getOptions()['sortProperty']);
    }
}
