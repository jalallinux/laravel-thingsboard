<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignDeviceToPublicCustomerTest extends TestCase
{
    public function testCorrectCustomerId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;

        $device = thingsboard($tenantUser)->device()->assignDeviceToPublicCustomer($deviceId);
        $this->assertInstanceOf(Device::class, $device);

        $device->unAssignDeviceFromCustomer();
    }
}
