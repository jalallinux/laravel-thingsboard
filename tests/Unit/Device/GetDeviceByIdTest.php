<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($user)->device()->getTenantDeviceInfos(
            PaginationArguments::make()
        )->data()->first()->id->id;

        $device = thingsboard($user)->device()->getDeviceById($deviceId);
        $this->assertEquals($deviceId, $device->id->id);

        $device = thingsboard($user)->device(['id' => new Id($deviceId, EnumEntityType::DEVICE())])->getDeviceById();
        $this->assertEquals($deviceId, $device->id->id);
        $this->assertInstanceOf(Device::class, $device);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->device()->getDeviceById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->device()->getDeviceById($this->faker->uuid);
    }
}
