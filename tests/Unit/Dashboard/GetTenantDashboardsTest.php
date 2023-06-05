<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDashboardSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantDashboardsTest extends TestCase
{
    public function testStructure()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantId = thingsboard($user)->tenant()->getTenants(PaginationArguments::make())->data()->first()->id->id;

        $dashboards = thingsboard($user)->dashboard()->getTenantDashboards(
            PaginationArguments::make(textSearch: 'Thermostats'), $tenantId
        );

        $dashboards->data()->each(fn ($dashboard) => $this->assertInstanceOf(Dashboard::class, $dashboard));
        self::assertStringContainsString('Thermostats', $dashboards->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDashboardSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantId = thingsboard($user)->tenant()->getTenants(PaginationArguments::make())->data()->first()->id->id;

        $dashboards = thingsboard()->dashboard(['tenantId' => new Id($tenantId, EnumEntityType::TENANT())])->withUser($user)->getTenantDashboards($pagination);

        $this->assertEquals($pagination->page, $dashboards->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $dashboards->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $dashboards->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $dashboards->paginator()->getOptions()['sortProperty']);
    }
}
