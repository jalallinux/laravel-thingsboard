<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnAssignDashboardFromPublicCustomerTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->data()->random()->id->id;
        $dashboard = thingsboard($tenantUser)->dashboard()->assignDashboardToPublicCustomer($dashboardId);

        $this->assertEquals($dashboardId, $dashboard->id->id);
        collect($dashboard->assignedCustomers)->each(function ($customer) {
            $this->assertEquals(EnumEntityType::CUSTOMER()->value, $customer['customerId']['entityType']);
            $this->assertTrue($customer['public']);
        });

        $dashboard = thingsboard($tenantUser)->dashboard()->unassignDashboardFromPublicCustomer($dashboardId);

        $this->assertEquals($dashboardId, $dashboard->id->id);
        $this->assertEquals([], $dashboard->assignedCustomers);
    }
}
