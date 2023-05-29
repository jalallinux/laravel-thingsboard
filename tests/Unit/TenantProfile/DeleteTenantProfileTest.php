<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\TenantProfile;

use JalalLinuX\Thingsboard\Entities\TenantProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteTenantProfileTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCorrectUuid()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantProfile = thingsboard($adminUser)->tenantProfile(['name' => $this->faker->sentence(3)])->saveTenantProfile();

        $this->assertInstanceOf(TenantProfile::class, $tenantProfile);
        $this->assertInstanceOf(Id::class, $tenantProfile->id);

        $result = $tenantProfile->deleteTenantProfile();
        self::assertTrue($result);
    }

    /**
     * @throws \Throwable
     */
    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($adminUser)->tenantProfile()->deleteTenantProfile($uuid);
    }
}
