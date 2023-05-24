<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Enums\ThingsboardUserRole;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetDeviceByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(ThingsboardUserRole::TENANT_ADMIN());
        $deviceId = thingsboard()->device()->withUser($user)->getTenantDeviceInfos(
            ThingsboardPaginationArguments::make()
        )->data()->first()->id->id;

        $device = thingsboard()->device()->withUser($user)->getDeviceById($deviceId);
        $this->assertEquals($deviceId, $device->id->id);

        $device = thingsboard()->device(['id' => $deviceId])->withUser($user)->getDeviceById();
        $this->assertEquals($deviceId, $device->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(ThingsboardUserRole::TENANT_ADMIN());

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);
        thingsboard()->device()->withUser($user)->getDeviceById(substr_replace(fake()->uuid, "z", -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(ThingsboardUserRole::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard()->device()->withUser($user)->getDeviceById(fake()->uuid);
    }
}
