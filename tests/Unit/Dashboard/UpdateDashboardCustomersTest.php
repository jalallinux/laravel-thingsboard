<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UpdateDashboardCustomersTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $customerIds = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->data()->pluck('id.id')
            ->take($count = $this->faker->numberBetween(1, 3))->toArray();

        $dashboard = thingsboard($tenantUser)->dashboard()->getDashboards(PaginationArguments::make())->data()->random();
        $dashboard = thingsboard($tenantUser)->dashboard()->updateDashboardCustomers($customerIds, $dashboard->id->id);

        $this->assertInstanceOf(Dashboard::class, $dashboard);
        $this->assertCount($count, $dashboard->assignedCustomers);
        collect($dashboard->assignedCustomers)->each(function ($customer) use ($customerIds) {
            $this->assertContains($customer['customerId']['id'], $customerIds);
        });

        thingsboard($tenantUser)->dashboard()->updateDashboardCustomers([], $dashboard->id->id);
    }
}
