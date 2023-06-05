<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Usage;

use JalalLinuX\Thingsboard\Entities\Usage;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantUsageInfoTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $usage = thingsboard($tenantUser)->usage()->getTenantUsageInfo();

        $this->assertInstanceOf(Usage::class, $usage);
        foreach ($usage->getFillable() as $fillable) {
            $this->assertArrayHasKey($fillable, $usage->toArray());
        }
    }

    public function testAuthorization()
    {
        $user = $this->thingsboardUser($this->faker->randomElement([EnumAuthority::SYS_ADMIN(), EnumAuthority::CUSTOMER_USER()]));

        $this->expectExceptionCode(403);
        $this->expectExceptionMessageMatches('/permission/');
        thingsboard($user)->usage()->getTenantUsageInfo();
    }
}
