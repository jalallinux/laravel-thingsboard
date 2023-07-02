<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantDeviceInfosTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $devices = thingsboard($user)->device()->getTenantDeviceInfos(
            PaginationArguments::make(textSearch: 'Raspberry')
        );

        $devices->collect()->each(fn ($device) => $this->assertInstanceOf(Device::class, $device));
        self::assertStringContainsString('Raspberry', $devices->collect()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDeviceSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $devices = thingsboard()->device()->withUser($user)->getTenantDeviceInfos($pagination);

        $this->assertEquals($pagination->page, $devices->currentPage());
        $this->assertEquals($pagination->pageSize, $devices->perPage());
        $this->assertEquals($pagination->sortOrder, $devices->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $devices->getOptions()['sortProperty']);
    }
}
