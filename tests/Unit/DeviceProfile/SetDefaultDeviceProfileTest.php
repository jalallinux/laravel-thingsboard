<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SetDefaultDeviceProfileTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testSetDefaultDeviceProfileSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceProfileId = thingsboard($tenantUser)->deviceProfile()->getDeviceProfiles(PaginationArguments::make())->collect()->first()->id->id;
        $defaultDeviceProfileId = thingsboard($tenantUser)->deviceProfile()->getDefaultDeviceProfileInfo()->id->id;

        $deviceProfile = thingsboard($tenantUser)->deviceProfile()->setDefaultDeviceProfile($deviceProfileId);
        thingsboard($tenantUser)->deviceProfile()
            ->setDefaultDeviceProfile($defaultDeviceProfileId);

        $this->assertInstanceOf(DeviceProfile::class, $deviceProfile);
        $this->assertInstanceOf(Id::class, $deviceProfile->id);
        $this->assertEquals($deviceProfileId, $deviceProfile->id->id);
        $this->assertTrue($deviceProfile->default);
    }

    public function testRequirementId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->deviceProfile()->setDefaultDeviceProfile();

    }
}
