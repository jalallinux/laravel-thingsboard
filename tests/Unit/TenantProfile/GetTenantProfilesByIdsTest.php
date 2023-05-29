<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantProfilesByIdsTest extends TestCase
{
    public function testSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantProfilesIds = thingsboard($adminUser)->tenantProfile()->getTenantProfileInfos(PaginationArguments::make())->data()->pluck('id.id')->toArray();
        $tenantProfilesIds = $this->faker->randomElements($tenantProfilesIds, $count = $this->faker->numberBetween(1, 2));
        $tenantProfiles = thingsboard($adminUser)->tenantProfile()->getTenantProfilesByIds($tenantProfilesIds);

        $this->assertCount($count, $tenantProfiles);
        collect($tenantProfiles)->each(function ($tenantProfile) use ($tenantProfilesIds) {
            $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
            $this->assertTrue(in_array($tenantProfile->id->id, $tenantProfilesIds));
        });
    }

    public function testNonExistUuid()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantProfiles = thingsboard($adminUser)->tenantProfile()->getTenantProfilesByIds([$this->faker->uuid, $this->faker->uuid]);

        $this->assertCount(0, $tenantProfiles);
    }
}
