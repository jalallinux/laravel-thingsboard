<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantProfileInfoByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantProfileId = thingsboard($adminUser)->tenantProfile()->getTenantProfileInfos(
            PaginationArguments::make()
        )->collect()->first()->id->id;

        $tenantProfile = thingsboard($adminUser)->tenantProfile()->getTenantProfileInfoById($tenantProfileId);
        $this->assertEquals($tenantProfileId, $tenantProfile->id->id);

        $tenantProfile = thingsboard($adminUser)->tenantProfile(['id' => new Id($tenantProfileId, EnumEntityType::TENANT_PROFILE())])->getTenantProfileInfoById();
        $this->assertEquals($tenantProfileId, $tenantProfile->id->id);

        $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
    }

   public function testInvalidUuid()
   {
       $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
       $this->expectException(Exception::class);
       $this->expectExceptionCode(500);
       thingsboard($adminUser)->tenantProfile()->getTenantProfileInfoById(substr_replace($this->faker->uuid, 'z', -1));
   }

    public function testNonExistUuid()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($adminUser)->tenantProfile()->getTenantProfileInfoById($this->faker->uuid);
    }
}
