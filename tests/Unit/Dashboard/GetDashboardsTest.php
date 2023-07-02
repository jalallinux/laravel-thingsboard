<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Entities\Dashboard;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDashboardSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDashboardsTest extends TestCase
{
    public function testStructure()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $dashboards = thingsboard($user)->dashboard()->getDashboards(
            PaginationArguments::make(textSearch: 'Thermostats')
        );

        $dashboards->collect()->each(fn ($dashboard) => $this->assertInstanceOf(Dashboard::class, $dashboard));
        self::assertStringContainsString('Thermostats', $dashboards->collect()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDashboardSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $dashboards = thingsboard()->dashboard()->withUser($user)->getDashboards($pagination);

        $this->assertEquals($pagination->page, $dashboards->currentPage());
        $this->assertEquals($pagination->pageSize, $dashboards->perPage());
        $this->assertEquals($pagination->sortOrder, $dashboards->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $dashboards->getOptions()['sortProperty']);
    }
}
