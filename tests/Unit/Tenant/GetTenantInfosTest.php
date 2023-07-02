<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use JalalLinuX\Thingsboard\Entities\Tenant;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumTenantSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantInfosTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenants = thingsboard($user)->tenant()->getTenantInfos(
            PaginationArguments::make()
        );

        $tenants->collect()->each(fn ($tenant) => $this->assertInstanceOf(Tenant::class, $tenant));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumTenantSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenants = thingsboard($user)->tenant()->getTenantInfos($pagination);

        $this->assertEquals($pagination->page, $tenants->currentPage());
        $this->assertEquals($pagination->pageSize, $tenants->perPage());
        $this->assertEquals($pagination->sortOrder, $tenants->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $tenants->getOptions()['sortProperty']);
    }
}
