<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SetDefaultTenantProfileTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testSetDefaultTenantProfileSuccess()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantProfileId = thingsboard($adminUser)->tenantProfile()->getTenantProfiles(PaginationArguments::make())->data()->first()->id->id;
        $defaultTenantProfileId = thingsboard($adminUser)->tenantProfile()->getDefaultTenantProfileInfo()->id->id;


        $tenantProfile = thingsboard($adminUser)->tenantProfile()->setDefaultTenantProfile($tenantProfileId, true);
        thingsboard($adminUser)->tenantProfile()
            ->setDefaultTenantProfile($defaultTenantProfileId);

        $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
        $this->assertInstanceOf(Id::class, $tenantProfile->id);
        $this->assertEquals($tenantProfileId, $tenantProfile->id->id);
        $this->assertTrue($tenantProfile->default);
    }

    public function testRequirementId()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($adminUser)->tenantProfile()->setDefaultTenantProfile();

    }
}
