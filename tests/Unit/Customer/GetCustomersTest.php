<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Customer;

use JalalLinuX\Thingsboard\Entities\Customer;
use JalalLinuX\Thingsboard\Enums\CustomerSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetCustomersTest extends TestCase
{
    public function testTextSearch()
    {
        $textSearch = $this->faker->randomElement(['A', 'B', 'C']);
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());
        $customers = thingsboard($user)->customer()->getCustomers(
            ThingsboardPaginationArguments::make(textSearch: $textSearch)
        );

        $customers->data()->each(fn ($customer) => $this->assertInstanceOf(Customer::class, $customer));
        $this->assertEquals("Customer {$textSearch}", $customers->data()->first()->title);
        $this->assertEquals("Customer {$textSearch}", $customers->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(CustomerSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());

        $customers = thingsboard($user)->customer()->getCustomers(
            ThingsboardPaginationArguments::make(
                page: $pagination['page'], pageSize: $pagination['pageSize'],
                sortProperty: $pagination['sortProperty'], sortOrder: $pagination['sortOrder']
            )
        );

        $this->assertEquals($pagination['page'], $customers->paginator()->currentPage());
        $this->assertEquals($pagination['pageSize'], $customers->paginator()->perPage());
        $this->assertEquals($pagination['sortOrder'], $customers->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination['sortProperty'], $customers->paginator()->getOptions()['sortProperty']);
    }
}
