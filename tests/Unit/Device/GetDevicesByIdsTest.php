<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDevicesByIdsTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceIds = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->pluck('id.id')->toArray();
        $deviceIds = $this->faker->randomElements($deviceIds, $count = $this->faker->numberBetween(1, 5));
        $devices = thingsboard($tenantUser)->device()->getDevicesByIds($deviceIds);

        $this->assertCount($count, $devices);
        collect($devices)->each(function ($device) use ($deviceIds) {
            $this->assertInstanceOf(Device::class, $device);
            $this->assertTrue(in_array($device->id->id, $deviceIds));
        });
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $devices = thingsboard($tenantUser)->device()->getDevicesByIds([$this->faker->uuid, $this->faker->uuid]);

        $this->assertCount(0, $devices);
    }
}
