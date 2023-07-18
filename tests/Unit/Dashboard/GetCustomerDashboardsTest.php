<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDashboardSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerDashboardsTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->collect()->random()->id->id;

        thingsboard($tenantUser)->dashboard()->assignDashboardToCustomer($customerId, $dashboardId);

        $dashboards = thingsboard($tenantUser)->dashboard()->getCustomerDashboards(
            $customerId, PaginationArguments::make()
        );

        $dashboards->collect()->each(function ($dashboard) {
            $this->assertInstanceOf(Dashboard::class, $dashboard);
        });

        thingsboard($tenantUser)->dashboard()->unassignDashboardFromCustomer($customerId, $dashboardId);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDashboardSortProperty::class);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->collect()->random()->id->id;

        thingsboard($tenantUser)->dashboard()->assignDashboardToCustomer($customerId, $dashboardId);
        $dashboards = thingsboard()->dashboard()->withUser($tenantUser)->getCustomerDashboards($customerId, $pagination);

        $this->assertEquals($pagination->page, $dashboards->currentPage());
        $this->assertEquals($pagination->pageSize, $dashboards->perPage());
        $this->assertEquals($pagination->sortOrder, $dashboards->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $dashboards->getOptions()['sortProperty']);

        thingsboard($tenantUser)->dashboard()->unassignDashboardFromCustomer($customerId, $dashboardId);
    }
}
