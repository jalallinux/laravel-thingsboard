<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceInfoByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($user)->device()->getTenantDeviceInfos(
            PaginationArguments::make()
        )->collect()->first()->id->id;

        $device = thingsboard($user)->device()->getDeviceInfoById($deviceId);
        $this->assertEquals($deviceId, $device->id->id);

        $device = thingsboard($user)->device(['id' => new Id($deviceId, EnumEntityType::DEVICE())])->getDeviceInfoById();
        $this->assertEquals($deviceId, $device->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->device()->getDeviceInfoById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->device()->getDeviceInfoById($this->faker->uuid);
    }
}
