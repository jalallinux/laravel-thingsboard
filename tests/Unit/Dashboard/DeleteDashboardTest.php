<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteDashboardTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $dashboard = thingsboard($tenantUser)->dashboard()->saveDashboard($title = "- {$this->faker->sentence(3)}", []);

        $result = thingsboard($tenantUser)->dashboard()->deleteDashboard($dashboard->id->id);
        $this->assertTrue($result);
    }
}
