<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use JalalLinuX\Thingsboard\Entities\Tenant;
use JalalLinuX\Thingsboard\Enums\TenantSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetTenantsTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(ThingsboardUserRole::SYS_ADMIN());
        $tenants = thingsboard($user)->tenant()->getTenants(
            ThingsboardPaginationArguments::make()
        );

        $tenants->data()->each(fn ($tenant) => $this->assertInstanceOf(Tenant::class, $tenant));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(TenantSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardUserRole::SYS_ADMIN());

        $tenants = thingsboard($user)->tenant()->getTenants(
            ThingsboardPaginationArguments::make(
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
