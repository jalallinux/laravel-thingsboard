<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class RemoveDashboardCustomersTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN(), 'tenant@thingsboard.org', 'tenant');
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make(textSearch: 'Customer'))->collect()->random()->id->id;
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->collect()->random()->id->id;

        $dashboard = thingsboard($tenantUser)->dashboard()->assignDashboardToCustomer($customerId, $dashboardId);

        $this->assertEquals($dashboardId, $dashboard->id->id);
        collect($dashboard->assignedCustomers)->each(function ($customer) use ($customerId) {
            $this->assertEquals(EnumEntityType::CUSTOMER()->value, $customer['customerId']['entityType']);
            $this->assertEquals($customerId, $customer['customerId']['id']);
        });

        $dashboard = thingsboard($tenantUser)->dashboard()->removeDashboardCustomers([$customerId], $dashboardId);

        $this->assertEquals($dashboardId, $dashboard->id->id);
        $this->assertEquals([], $dashboard->assignedCustomers);
    }
}
