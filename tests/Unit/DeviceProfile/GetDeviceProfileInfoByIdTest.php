<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceProfileInfoByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceProfileId = thingsboard($user)->deviceProfile()->getDeviceProfiles(
            PaginationArguments::make()
        )->data()->first()->id->id;

        $deviceProfile = thingsboard($user)->deviceProfile()->getDeviceProfileInfoById($deviceProfileId);
        $this->assertEquals($deviceProfileId, $deviceProfile->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->deviceProfile()->getDeviceProfileInfoById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->deviceProfile()->getDeviceProfileInfoById($this->faker->uuid);
    }
}
