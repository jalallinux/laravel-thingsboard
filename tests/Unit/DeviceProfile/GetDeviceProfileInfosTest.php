<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceProfileInfosTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard($user)->deviceProfile()->getDeviceProfileInfos(PaginationArguments::make(sortProperty: EnumDeviceProfileSortProperty::TRANSPORT_TYPE(), sortOrder: EnumSortOrder::ASC()));

        $deviceProfiles->data()->each(fn ($device) => $this->assertInstanceOf(DeviceProfile::class, $device));
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDeviceProfileSortProperty::class);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard()->deviceProfile()->withUser($tenantUser)->getDeviceProfileInfos($pagination);

        $this->assertEquals($pagination->page, $deviceProfiles->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $deviceProfiles->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $deviceProfiles->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $deviceProfiles->paginator()->getOptions()['sortProperty']);
    }
}
