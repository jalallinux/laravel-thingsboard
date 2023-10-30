<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnAssignDeviceFromEdgeTest extends TestCase
{
    public function testCorrectDeviceId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $device = thingsboard($tenantUser)->device()->assignDeviceToEdge($edge->id->id, $deviceId);
        $this->assertInstanceOf(Device::class, $device);

        $device = thingsboard($tenantUser)->device()->unassignDeviceFromEdge($edge->id->id, $deviceId);
        $this->assertInstanceOf(Device::class, $device);

        $edge->deleteEdge();
    }
}
