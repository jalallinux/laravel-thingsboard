<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDefaultTenantProfileInfoTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenantProfile = thingsboard($tenantUser)->tenantProfile()->getDefaultTenantProfileInfo();
        $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
        $this->assertEquals($tenantProfile->name, 'Default');

        $tenantProfile = thingsboard($tenantUser)->tenantProfile()->getDefaultTenantProfileInfo(true);
        $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
        $this->assertTrue($tenantProfile->default);
        $this->assertEquals($tenantProfile->name, 'Default');
    }
}
