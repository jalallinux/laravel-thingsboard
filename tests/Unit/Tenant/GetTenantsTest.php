<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use JalalLinuX\Thingsboard\Entities\Tenant;
use JalalLinuX\Thingsboard\Enums\TenantSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantsTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());
        $tenants = thingsboard($user)->tenant()->getTenants(
            PaginationArguments::make()
        );

        $tenants->data()->each(fn ($tenant) => $this->assertInstanceOf(Tenant::class, $tenant));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(TenantSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());

        $tenants = thingsboard($user)->tenant()->getTenants(
            PaginationArguments::make(
                page: $pagination['page'], pageSize: $pagination['pageSize'],
                sortProperty: $pagination['sortProperty'], sortOrder: $pagination['sortOrder']
            )
        );

        $this->assertEquals($pagination['page'], $tenants->paginator()->currentPage());
        $this->assertEquals($pagination['pageSize'], $tenants->paginator()->perPage());
        $this->assertEquals($pagination['sortOrder'], $tenants->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination['sortProperty'], $tenants->paginator()->getOptions()['sortProperty']);
    }
}
