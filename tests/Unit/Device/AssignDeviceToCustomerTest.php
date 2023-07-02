<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignDeviceToCustomerTest extends TestCase
{
    public function testCorrectCustomerId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->first()->id->id;

        $device = thingsboard($tenantUser)->device()->assignDeviceToCustomer($customerId, $deviceId);
        $this->assertInstanceOf(Device::class, $device);
        $this->assertEquals($customerId, $device->customerId->id);

        $device->unAssignDeviceFromCustomer();
    }

    public function testInvalidCustomerUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->device()->assignDeviceToCustomer($uuid, $deviceId);
    }
}
