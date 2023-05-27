<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetDeviceByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($user)->device()->getTenantDeviceInfos(
            ThingsboardPaginationArguments::make()
        )->data()->first()->id->id;

        $device = thingsboard($user)->device()->getDeviceById($deviceId);
        $this->assertEquals($deviceId, $device->id->id);

        $device = thingsboard($user)->device(['id' => new ThingsboardId($deviceId, ThingsboardEntityType::DEVICE())])->getDeviceById();
        $this->assertEquals($deviceId, $device->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->device()->getDeviceById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->device()->getDeviceById($this->faker->uuid);
    }
}
