<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use JalalLinuX\Thingsboard\Entities\Tenant;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteTenantTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenant = thingsboard($tenantUser)->tenant(['title' => $this->faker->sentence(3)])->saveTenant();

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertInstanceOf(Id::class, $tenant->id);

        $result = $tenant->deleteTenant();
        self::assertTrue($result);
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->tenant()->deleteTenant($uuid);
    }
}
