<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryAggregation;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetLatestTimeseriesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetLatestTimeseriesSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $keys = thingsboard($tenantUser)->telemetry()->getTimeseriesKeys(EnumEntityType::DEVICE(), $deviceId);
        $result = thingsboard($tenantUser)->telemetry()->getLatestTimeseries(new Id($deviceId, EnumEntityType::DEVICE()), $keys,
            $this->faker->boolean()
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
        thingsboard($tenantUser)->telemetry()->getLatestTimeseries(new Id($uuid, EnumEntityType::DEVICE()), $keys,
            $this->faker->boolean()
        );
    }
}
