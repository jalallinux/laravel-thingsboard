<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\DeviceProfile;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTimeseriesKeysTest extends TestCase
{
    public function testGetTimeseriesKeysSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceProfileId = thingsboard($tenantUser)->deviceProfile()->getDeviceProfiles(PaginationArguments::make())->data()->first()->id->id;
        $timeseriesKeys = thingsboard($tenantUser)->deviceProfile()->getTimeseriesKeys($deviceProfileId);
        $this->assertIsArray($timeseriesKeys);
    }
    public function testGetTimeseriesKeysWithoutUuidSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $timeseriesKeys = thingsboard($tenantUser)->deviceProfile()->getTimeseriesKeys();
        $this->assertIsArray($timeseriesKeys);
    }
}
