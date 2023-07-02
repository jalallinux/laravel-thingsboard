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
        $customerLetter = $this->faker->randomElement(['A', 'B', 'C']);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customers = thingsboard($user)->customer()->getCustomers(
            PaginationArguments::make(textSearch: "Customer {$customerLetter}")
        );

        $customers->collect()->each(fn ($customer) => $this->assertInstanceOf(Customer::class, $customer));
        $this->assertEquals("Customer {$customerLetter}", $customers->collect()->first()->title);
        $this->assertEquals("Customer {$customerLetter}", $customers->collect()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumCustomerSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $customers = thingsboard($user)->customer()->getCustomers($pagination);

        $this->assertEquals($pagination->page, $customers->currentPage());
        $this->assertEquals($pagination->pageSize, $customers->perPage());
        $this->assertEquals($pagination->sortOrder, $customers->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $customers->getOptions()['sortProperty']);
    }
}
