<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\ProfileData;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantProfileByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantProfileId = thingsboard($user)->tenantProfile()->getTenantProfileInfos(
            PaginationArguments::make()
        )->data()->first()->id->id;

        $tenantProfile = thingsboard($user)->tenantProfile()->getTenantProfileById($tenantProfileId);
        $this->assertEquals($tenantProfileId, $tenantProfile->id->id);

        $tenantProfile = thingsboard($user)->tenantProfile(['id' => new Id($tenantProfileId, EnumEntityType::TENANT_PROFILE())])->getTenantProfileById();
        $this->assertEquals($tenantProfileId, $tenantProfile->id->id);

        $this->assertInstanceOf(ProfileData::class, $tenantProfile->profileData);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->tenantProfile()->getTenantProfileById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->tenantProfile()->getTenantProfileById($this->faker->uuid);
    }
}
