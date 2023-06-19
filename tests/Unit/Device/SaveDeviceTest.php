<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveDeviceTest extends TestCase
{
    public function testCreateDeviceSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $defaultDeviceProfileId = thingsboard($tenantUser)->deviceProfile()->getDefaultDeviceProfileInfo()->id;
        $attributes = [
            'deviceProfileId' => $defaultDeviceProfileId,
            'name' => $this->faker->sentence(3),
            'label' => $this->faker->sentence(3),
        ];
        $device = thingsboard($tenantUser)->device($attributes)->saveDevice('ACCESS_TOKEN_'.$this->faker->numerify);
        $device->deleteDevice();

        $this->assertInstanceOf(Device::class, $device);
        $this->assertInstanceOf(Id::class, $device->id);
        $this->assertEquals($attributes['name'], $device->name);
        $this->assertEquals($attributes['label'], $device->label);
        $this->assertEquals($attributes['deviceProfileId']->id, $device->deviceProfileId->id);
    }

    public function testRequiredProperty()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($user)->device(Arr::except($attributes, 'name'))->saveDevice();
    }

    public function testExistsName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceName = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->name;
        $attributes = [
            'name' => $deviceName,
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($tenantUser)->device($attributes)->saveDevice();
    }
}
