<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Dashboard;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDashboardInfoByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $dashboardId = thingsboard($user)->dashboard()->getDashboards(PaginationArguments::make())->data()->first()->id->id;

        $dashboard = thingsboard($user)->dashboard()->getDashboardInfoById($dashboardId);
        $this->assertEquals($dashboardId, $dashboard->id->id);

        $dashboard = thingsboard($user)->dashboard(['id' => new Id($dashboardId, EnumEntityType::DEVICE())])->getDashboardById();
        $this->assertEquals($dashboardId, $dashboard->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->dashboard()->getDashboardInfoById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->dashboard()->getDashboardInfoById($this->faker->uuid);
    }
}
