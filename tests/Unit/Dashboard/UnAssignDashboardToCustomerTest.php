<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnAssignDashboardToCustomerTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make(textSearch: 'Customer'))->data()->random()->id->id;
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->data()->random()->id->id;

        $dashboard = thingsboard($tenantUser)->dashboard()->assignDashboardToCustomer($customerId, $dashboardId);

        $this->assertEquals($dashboardId, $dashboard->id->id);
        collect($dashboard->assignedCustomers)->each(function ($customer) use ($customerId) {
            $this->assertEquals(EnumEntityType::CUSTOMER()->value, $customer['customerId']['entityType']);
            $this->assertEquals($customerId, $customer['customerId']['id']);
        });

        $dashboard = thingsboard($tenantUser)->dashboard()->unassignDashboardFromCustomer($customerId, $dashboardId);

        $this->assertEquals($dashboardId, $dashboard->id->id);
        $this->assertEquals([], $dashboard->assignedCustomers);
    }
}
