<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Entities\DeviceProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceProfilesTest extends TestCase
{
    public function testTextSearch()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard($user)->deviceProfile()->getDeviceProfiles(
            PaginationArguments::make(textSearch: 'default')
        );

        $deviceProfiles->collect()->each(fn ($device) => $this->assertInstanceOf(DeviceProfile::class, $device));
        self::assertStringContainsString('default', $deviceProfiles->collect()->first()->name);
    }

    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumDeviceProfileSortProperty::class);
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $deviceProfiles = thingsboard()->deviceProfile()->withUser($user)->getDeviceProfiles($pagination);

        $this->assertEquals($pagination->page, $deviceProfiles->currentPage());
        $this->assertEquals($pagination->pageSize, $deviceProfiles->perPage());
        $this->assertEquals($pagination->sortOrder, $deviceProfiles->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $deviceProfiles->getOptions()['sortProperty']);
    }
}
