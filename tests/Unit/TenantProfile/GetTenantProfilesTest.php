<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumTenantProfileSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantProfilesTest extends TestCase
{
    public function testSortProperty()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumTenantProfileSortProperty::cases());
        $tenantProfiles = thingsboard($adminUser)->tenantProfile()->getTenantProfiles(
            PaginationArguments::make(sortProperty: $sortProperty)
        );

        $tenantProfiles->data()->each(fn ($tenantProfile) => $this->assertInstanceOf(TenantProfile::class, $tenantProfile));
    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(EnumTenantProfileSortProperty::cases());
        $pagination = $this->randomPagination([$sortProperty]);
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenantProfiles = thingsboard($adminUser)->tenantProfile()->getTenantProfiles($pagination);

        $this->assertEquals($pagination->page, $tenantProfiles->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $tenantProfiles->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $tenantProfiles->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $tenantProfiles->paginator()->getOptions()['sortProperty']);
    }
}
