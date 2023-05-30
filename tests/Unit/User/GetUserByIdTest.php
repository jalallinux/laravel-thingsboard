<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetUserByIdTest extends TestCase
{
    public function testCorrectUuid()
    {
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $tenantId = thingsboard()->tenant()->withUser($adminUser)->getTenants(PaginationArguments::make())->data()->first()->id->id;
        $userId = thingsboard($adminUser)->user()->getTenantAdmins(PaginationArguments::make(), $tenantId)->data()->first()->id->id;
        $user = thingsboard($adminUser)->user()->getUserById($userId);

        dd($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userId, $user->id->id);
        $this->assertEquals(EnumAuthority::TENANT_ADMIN(), $user->authority);
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $adminUser = $this->thingsboardUser(EnumAuthority::SYS_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($adminUser)->user()->getUserById($uuid);
    }
}
