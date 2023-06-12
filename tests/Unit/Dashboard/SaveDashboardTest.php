<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveDashboardTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $dashboard = thingsboard($tenantUser)->dashboard()->saveDashboard($title = "- {$this->faker->sentence(3)}", []);
        thingsboard($tenantUser)->dashboard()->deleteDashboard($dashboard->id->id);

        $this->assertInstanceOf(Dashboard::class, $dashboard);
        $this->assertInstanceOf(Id::class, $dashboard->id);
        $this->assertEquals($title, $dashboard->title);
    }
}
