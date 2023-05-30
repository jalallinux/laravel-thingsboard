<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileProvisionType;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileTransportType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveDeviceProfileTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCreateDeviceProfileSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(5),
            'provisionType' => EnumDeviceProfileProvisionType::DISABLED(),
            'transportType' => EnumDeviceProfileTransportType::DEFAULT(),

        ];
        $deviceProfile = thingsboard($tenantUser)->deviceProfile($attributes)->saveDeviceProfile();

        $deviceProfile->deleteDeviceProfile();

        $this->assertInstanceOf(DeviceProfile::class, $deviceProfile);
        $this->assertInstanceOf(Id::class, $deviceProfile->id);
        $this->assertEquals($attributes['name'], $deviceProfile->name);
        $this->assertEquals($attributes['description'], $deviceProfile->description);
    }

    public function testRequiredProperty()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $attributes = [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(5),
            'provisionType' => EnumDeviceProfileProvisionType::DISABLED(),
            'transportType' => EnumDeviceProfileTransportType::DEFAULT(),

        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($tenantUser)->deviceProfile(Arr::except($attributes, 'name'))->saveDeviceProfile();
    }

    public function testExistsName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceName = thingsboard($tenantUser)->deviceProfile()->getDeviceProfiles(PaginationArguments::make())->data()->first()->name;
        $attributes = [
            'name' => $deviceName,
            'description' => $this->faker->sentence(5),
            'provisionType' => EnumDeviceProfileProvisionType::DISABLED(),
            'transportType' => EnumDeviceProfileTransportType::DEFAULT(),

        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($tenantUser)->deviceProfile($attributes)->saveDeviceProfile();
    }
}
