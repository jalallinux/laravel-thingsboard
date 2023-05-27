<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\DeviceSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetTenantDeviceInfosTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());

        $devices = thingsboard($user)->device()->getTenantDeviceInfos(
            ThingsboardPaginationArguments::make(textSearch: 'Raspberry')
        );

        $devices->data()->each(fn ($device) => $this->assertInstanceOf(Device::class, $device));
        self::assertStringContainsString('Raspberry', $devices->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(DeviceSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardAuthority::TENANT_ADMIN());

        $devices = thingsboard()->device()->withUser($user)->getTenantDeviceInfos(
            ThingsboardPaginationArguments::make(
                page: $pagination['page'], pageSize: $pagination['pageSize'],
                sortProperty: $pagination['sortProperty'], sortOrder: $pagination['sortOrder']
            )
        );

        $this->assertEquals($pagination['page'], $devices->paginator()->currentPage());
        $this->assertEquals($pagination['pageSize'], $devices->paginator()->perPage());
        $this->assertEquals($pagination['sortOrder'], $devices->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination['sortProperty'], $devices->paginator()->getOptions()['sortProperty']);
    }
}
