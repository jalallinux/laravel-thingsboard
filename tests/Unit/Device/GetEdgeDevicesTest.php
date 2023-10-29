<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeDevicesTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        thingsboard($tenantUser)->device()->assignDeviceToEdge($edge->id->id, $deviceId);

        $devices = thingsboard($tenantUser)->device()->getEdgeDevices(PaginationArguments::make(), $edge->id->id);
        $edge->deleteEdge();

        $devices->each(function ($device) use ($deviceId) {
            $this->assertInstanceOf(Device::class, $device);
            $this->assertTrue($device->id->id == $deviceId);
        });
    }
}
