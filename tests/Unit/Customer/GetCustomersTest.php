<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Customer;

use JalalLinuX\Thingsboard\Entities\Customer;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumCustomerSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomersTest extends TestCase
{
    public function testTextSearch()
    {
        $textSearch = $this->faker->randomElement(['A', 'B', 'C']);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customers = thingsboard($user)->customer()->getCustomers(
            PaginationArguments::make(textSearch: $textSearch)
        );

        $customers->data()->each(fn ($customer) => $this->assertInstanceOf(Customer::class, $customer));
        $this->assertEquals("Customer {$textSearch}", $customers->data()->first()->title);
        $this->assertEquals("Customer {$textSearch}", $customers->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumCustomerSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $customers = thingsboard($user)->customer()->getCustomers($pagination);

        $this->assertEquals($pagination->page, $customers->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $customers->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $customers->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $customers->paginator()->getOptions()['sortProperty']);
    }
}
