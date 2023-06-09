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
        $sortProperty = $this->faker->randomElement(array_diff(EnumTenantSortProperty::cases(), [EnumTenantSortProperty::TENANT_PROFILE_NAME()]));
        $tenants = thingsboard($user)->tenant()->getTenants(
            PaginationArguments::make(sortProperty: $sortProperty)
        );

        $tenants->collect()->each(fn ($tenant) => $this->assertInstanceOf(Tenant::class, $tenant));
    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(array_diff(EnumTenantSortProperty::cases(), [EnumTenantSortProperty::TENANT_PROFILE_NAME()]));
        $pagination = $this->randomPagination([$sortProperty]);
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenants = thingsboard($adminUser)->tenant()->getTenants($pagination);

        $this->assertEquals($pagination->page, $tenants->currentPage());
        $this->assertEquals($pagination->pageSize, $tenants->perPage());
        $this->assertEquals($pagination->sortOrder, $tenants->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $tenants->getOptions()['sortProperty']);
    }
}
