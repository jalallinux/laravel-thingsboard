<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnAssignDashboardFromEdgeTest extends TestCase
{
    public function testCorrectCustomerId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->collect()->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $dashboard = thingsboard($tenantUser)->dashboard()->assignDashboardToEdge($edge->id->id, $dashboardId);
        $this->assertInstanceOf(Dashboard::class, $dashboard);

        $dashboard = thingsboard($tenantUser)->dashboard()->unassignDashboardFromEdge($edge->id->id, $dashboardId);
        $this->assertInstanceOf(Dashboard::class, $dashboard);
    }
}
