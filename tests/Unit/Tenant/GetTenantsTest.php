<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use JalalLinuX\Thingsboard\Entities\Tenant;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumTenantSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantsTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenants = thingsboard($user)->tenant()->getTenants(
            PaginationArguments::make()
        );

        $tenants->data()->each(fn ($tenant) => $this->assertInstanceOf(Tenant::class, $tenant));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumTenantSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenants = thingsboard($user)->tenant()->getTenants($pagination);

        $this->assertEquals($pagination->page, $tenants->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $tenants->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $tenants->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $tenants->paginator()->getOptions()['sortProperty']);
    }
}
