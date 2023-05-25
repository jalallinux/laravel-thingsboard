<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Enums\UserSortProperty;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetCustomerUsersTest extends TestCase
{
    public function testTextSearch()
    {
        $customerLetter = $this->faker->randomElement(['A', 'B', 'C']);
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

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(UserSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardUserRole::TENANT_ADMIN());
        $customerId = thingsboard()->customer()->withUser($user)->getCustomers(
            ThingsboardPaginationArguments::make()
        )->data()->first()->id->id;

        $devices = thingsboard()->user(['customerId' => $customerId])->withUser($user)->getCustomerUsers(
            ThingsboardPaginationArguments::make(
                page: $pagination['page'], pageSize: $pagination['pageSize'],
                sortProperty: $pagination['sortProperty'], sortOrder: $pagination['sortOrder']
            )
        );

        $this->assertEquals($pagination['page'], $devices->paginator()->currentPage());
        $this->assertEquals($pagination['pageSize'], $devices->paginator()->perPage());
        $this->assertEquals($pagination['sortOrder'], $devices->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination['sortProperty'], $devices->paginator()->getOptions()['sortProperty']);
    }
}
