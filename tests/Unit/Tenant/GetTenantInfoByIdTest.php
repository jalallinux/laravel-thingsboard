<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Tenant;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantInfoByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());
        $tenantId = thingsboard($user)->tenant()->getTenantInfos(
            PaginationArguments::make()
        )->collect()->first()->id->id;

        $tenant = thingsboard($user)->tenant()->getTenantInfoById($tenantId);
        $this->assertEquals($tenantId, $tenant->id->id);

        $tenant = thingsboard($user)->tenant(['id' => new Id($tenantId, EnumEntityType::DEVICE())])->getTenantInfoById();
        $this->assertEquals($tenantId, $tenant->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->tenant()->getTenantInfoById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->tenant()->getTenantInfoById($this->faker->uuid);
    }
}
