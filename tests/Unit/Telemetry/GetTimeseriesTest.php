<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTimeseriesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetTimeseriesSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $keys = ['key1', 'key2'];
        $result = thingsboard($tenantUser)->telemetry()->getTimeseries(new Id($deviceId, EnumEntityType::DEVICE()), $keys,
            now()->subDay()
        );
        self::assertIsArray($result);
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $uuid = $this->faker->uuid;
        $keys = ['key1', 'key2'];
        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->telemetry()->getTimeseries(new Id($uuid, EnumEntityType::DEVICE()), $keys,
            now()->subDay()
        );
    }
}
