<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceProfileByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceProfileId = thingsboard($user)->deviceProfile()->getDeviceProfiles(
            PaginationArguments::make()
        )->collect()->first()->id->id;

        $deviceProfile = thingsboard($user)->deviceProfile()->getDeviceProfileById($deviceProfileId);
        $this->assertEquals($deviceProfileId, $deviceProfile->id->id);

        $deviceProfile = thingsboard($user)->deviceProfile(['id' => new Id($deviceProfileId, EnumEntityType::DEVICE_PROFILE())])->getDeviceProfileById();
        $this->assertEquals($deviceProfileId, $deviceProfile->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->deviceProfile()->getDeviceProfileById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->deviceProfile()->getDeviceProfileById($this->faker->uuid);
    }
}
