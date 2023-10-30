<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeDashboardTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $dashboardId = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        thingsboard($tenantUser)->dashboard()->assignDashboardToEdge($edge->id->id, $dashboardId);

        $dashboards = thingsboard($tenantUser)->dashboard()->getEdgeDashboards(PaginationArguments::make(), $edge->id->id);
        $edge->deleteEdge();

        $dashboards->each(function ($dashboard) use ($dashboardId) {
            $this->assertInstanceOf(Dashboard::class, $dashboard);
            $this->assertTrue($dashboard->id->id == $dashboardId);
        });
    }
}
