<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\User;

use JalalLinuX\Thingsboard\Entities\User;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetUserByIdTest extends TestCase
{
    public function testCorrectUuid()
    {
        $adminUser = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());

        $tenantId = thingsboard()->tenant()->withUser($adminUser)->getTenants(ThingsboardPaginationArguments::make())->data()->first()->id->id;
        $userId = thingsboard($adminUser)->user()->getTenantAdmins(ThingsboardPaginationArguments::make(), $tenantId)->data()->first()->id->id;

        $user = thingsboard($adminUser)->user()->getUserById($userId);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userId, $user->id->id);
        $this->assertEquals(ThingsboardAuthority::TENANT_ADMIN(), $user->authority);
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $adminUser = $this->thingsboardUser(ThingsboardAuthority::SYS_ADMIN());

        $tenantId = thingsboard()->tenant()->withUser($adminUser)->getTenants(ThingsboardPaginationArguments::make())->data()->first()->id->id;
        $userId = thingsboard($adminUser)->user()->getTenantAdmins(ThingsboardPaginationArguments::make(), $tenantId)->data()->first()->id->id;

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($adminUser)->user()->getUserById($uuid);
    }
}
