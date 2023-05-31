<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveEntityTelemetryTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testSaveEntityTelemetrySuccess()
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
        $result = thingsboard($tenantUser)->telemetry()->saveEntityTelemetry($payload, EnumEntityType::DEVICE(), $deviceId);
        thingsboard($tenantUser)->telemetry()->deleteEntityTimeseries(EnumEntityType::DEVICE(), $deviceId, $payload[0]['values'], true);
        $this->assertTrue($result);
    }


        public function testInvalidPayload()
        {
            $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
            $this->expectExceptionCode(500);
            $this->expectExceptionMessageMatches('/ts/');
            thingsboard($tenantUser)->telemetry()->saveEntityTelemetry([], EnumEntityType::DEVICE(), $this->faker->uuid);
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
            thingsboard($tenantUser)->telemetry()->saveEntityTelemetry($payload, EnumEntityType::DEVICE(), substr_replace($this->faker->uuid, 'z', -1), EnumTelemetryScope::SHARED_SCOPE());
        }
}
