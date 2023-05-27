<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceProfilesTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard($user)->deviceProfile()->getDeviceProfiles(
            PaginationArguments::make(textSearch: 'default')
        );

        $deviceProfiles->data()->each(fn ($device) => $this->assertInstanceOf(DeviceProfile::class, $device));
        self::assertStringContainsString('default', $deviceProfiles->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDeviceProfileSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard()->deviceProfile()->withUser($user)->getDeviceProfiles(
            PaginationArguments::make(
                page: $pagination['page'], pageSize: $pagination['pageSize'],
                sortProperty: $pagination['sortProperty'], sortOrder: $pagination['sortOrder']
            )
        );

        $this->assertEquals($pagination['page'], $deviceProfiles->paginator()->currentPage());
        $this->assertEquals($pagination['pageSize'], $deviceProfiles->paginator()->perPage());
        $this->assertEquals($pagination['sortOrder'], $deviceProfiles->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination['sortProperty'], $deviceProfiles->paginator()->getOptions()['sortProperty']);
    }
}
