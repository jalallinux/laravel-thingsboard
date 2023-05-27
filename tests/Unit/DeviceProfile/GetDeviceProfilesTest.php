<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\DeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardUserAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;

class GetDeviceProfilesTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(ThingsboardUserAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard($user)->deviceProfile()->getDeviceProfiles(
            ThingsboardPaginationArguments::make(textSearch: 'default')
        );

        $deviceProfiles->data()->each(fn ($device) => $this->assertInstanceOf(DeviceProfile::class, $device));
        self::assertStringContainsString('default', $deviceProfiles->data()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(DeviceProfileSortProperty::class);
        $user = $this->thingsboardUser(ThingsboardUserAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard()->deviceProfile()->withUser($user)->getDeviceProfiles(
            ThingsboardPaginationArguments::make(
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
