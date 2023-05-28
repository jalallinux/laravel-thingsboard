<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDefaultDeviceProfileInfoTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfile = thingsboard($tenantUser)->deviceProfile()->getDefaultDeviceProfileInfo();
        $this->assertInstanceOf(DeviceProfile::class, $deviceProfile);
        $this->assertEquals($deviceProfile->name, 'default');

        $deviceProfile = thingsboard($tenantUser)->deviceProfile()->getDefaultDeviceProfileInfo(true);
        $this->assertInstanceOf(DeviceProfile::class, $deviceProfile);
        $this->assertTrue($deviceProfile->default);
        $this->assertEquals($deviceProfile->name, 'default');
    }
}
