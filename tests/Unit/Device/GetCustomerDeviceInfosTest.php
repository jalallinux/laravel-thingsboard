<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerDeviceInfosTest extends TestCase
{
    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDeviceSortProperty::class, 1, 20);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->random()->id->id;
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $device = thingsboard($tenantUser)->device()->assignDeviceToCustomer($customerId, $deviceId);

        $devices = thingsboard($tenantUser)->device()->getCustomerDeviceInfos($pagination, $customerId);

        $devices->collect()->each(fn ($device) => $this->assertInstanceOf(Device::class, $device));
        $device->unAssignDeviceFromCustomer();

        $this->assertEquals($pagination->page, $devices->currentPage());
        $this->assertEquals($pagination->pageSize, $devices->perPage());
        $this->assertEquals($pagination->sortOrder, $devices->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $devices->getOptions()['sortProperty']);
    }
}
