<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileProvisionType;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileTransportType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteDeviceProfileTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(5),
            'provisionType' => EnumDeviceProfileProvisionType::DISABLED(),
            'transportType' => EnumDeviceProfileTransportType::DEFAULT()

        ];
        $deviceProfile = thingsboard($tenantUser)->deviceProfile($attributes)->saveDeviceProfile();

        $this->assertInstanceOf(DeviceProfile::class, $deviceProfile);
        $this->assertInstanceOf(Id::class, $deviceProfile->id);

        $result = $deviceProfile->deleteDeviceProfile();
        self::assertTrue($result);
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->deviceProfile()->deleteDeviceProfile($uuid);
    }
}
