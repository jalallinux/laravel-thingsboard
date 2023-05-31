<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteEntityTimeseriesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testDeleteEntityTelemetrySuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $payload = [
            [
                'ts' => now()->timestamp * 1000,
                'values' => [
                    "temperature" => 26,
                    "humidity" => 87
                ]
            ]
        ];
        $ttl = (int)now()->getPreciseTimestamp(3);
        thingsboard($tenantUser)->telemetry()->saveEntityTelemetryWithTTL($payload, EnumEntityType::DEVICE(), $deviceId, $ttl);
        $result = thingsboard($tenantUser)->telemetry()->deleteEntityTimeseries(EnumEntityType::DEVICE(), $deviceId, $payload[0]['values'], true);
        $this->assertTrue($result);
    }

        public function testInvalidUuid()
        {
            $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
            $payload = [
                [
                    'ts' => now()->timestamp * 1000,
                    'values' => [
                        "temperature" => 26,
                        "humidity" => 87
                    ]
                ]
            ];
            $this->expectExceptionCode(500);
            $this->expectExceptionMessageMatches('/entityId/');
            thingsboard($tenantUser)->telemetry()->deleteEntityTimeseries(EnumEntityType::DEVICE(), substr_replace($this->faker->uuid, 'z', -1), $payload[0]['values'], true);
        }

        public function testIfStartAndEndTimestampWasNull()
        {
            $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
            $payload = [
                [
                    'ts' => now()->timestamp * 1000,
                    'values' => [
                        "temperature" => 26,
                        "humidity" => 87
                    ]
                ]
            ];
            $this->expectExceptionCode(500);
            $this->expectExceptionMessageMatches('/startTs/');
            thingsboard($tenantUser)->telemetry()->deleteEntityTimeseries(EnumEntityType::DEVICE(), $this->faker->uuid, $payload[0]['values'], false);
        }
}
