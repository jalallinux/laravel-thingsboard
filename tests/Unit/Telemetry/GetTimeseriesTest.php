<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryAggregation;
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
        $keys = thingsboard($tenantUser)->telemetry()->getTimeseriesKeys(EnumEntityType::DEVICE(), $deviceId);
        $result = thingsboard($tenantUser)->telemetry()->getTimeseries(EnumEntityType::DEVICE(), $deviceId, $keys,
            now()->subDay()->getPreciseTimestamp(3),
            now()->getPreciseTimestamp(3),
            $this->faker->biasedNumberBetween(1000, 10000), $this->faker->numberBetween(10, 1000),
            EnumTelemetryAggregation::SUM(), EnumSortOrder::ASC(), $this->faker->boolean()
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
        thingsboard($tenantUser)->telemetry()->getTimeseries(EnumEntityType::DEVICE(), $uuid, $keys,
            now()->subDay()->getPreciseTimestamp(3),
            now()->getPreciseTimestamp(3),
            $this->faker->biasedNumberBetween(1000, 10000), $this->faker->numberBetween(10, 1000),
            EnumTelemetryAggregation::SUM(), EnumSortOrder::ASC(), $this->faker->boolean()
        );
    }
}
