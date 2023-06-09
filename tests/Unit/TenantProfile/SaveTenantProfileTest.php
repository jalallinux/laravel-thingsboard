<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\ProfileData;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveTenantProfileTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCreateTenantProfileSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $attributes = [
            'name' => 'new one',
        ];
        $tenantProfile = thingsboard($tenantUser)->tenantProfile($attributes)->saveTenantProfile();
        $tenantProfile->deleteTenantProfile();

        $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
        $this->assertInstanceOf(Id::class, $tenantProfile->id);
        $this->assertEquals($attributes['name'], $tenantProfile->name);
        $this->assertInstanceOf(ProfileData::class, $tenantProfile->profileData);
    }

    public function testRequiredProperty()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($adminUser)->tenantProfile()->saveTenantProfile();
    }
}
