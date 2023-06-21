<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\Id;
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
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $payload = [
            [
                'ts' => now()->timestamp * 1000,
                'values' => [
                    'temperature' => 26,
                    'humidity' => 87,
                ],
            ],
        ];
        $result = thingsboard($tenantUser)->telemetry()->saveEntityTelemetry(new Id($deviceId, EnumEntityType::DEVICE()), $payload);
        thingsboard($tenantUser)->telemetry()->deleteEntityTimeseries(new Id($deviceId, EnumEntityType::DEVICE()), $payload[0]['values'], true);
        $this->assertTrue($result);
    }

    public function testInvalidPayload()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/ts/');
        thingsboard($tenantUser)->telemetry()->saveEntityTelemetry(new Id($this->faker->uuid, EnumEntityType::DEVICE()), []);
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $payload = [
            [
                'ts' => now()->timestamp * 1000,
                'values' => [
                    'temperature' => 26,
                    'humidity' => 87,
                ],
            ],
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->telemetry()->saveEntityTelemetry(new Id(substr_replace($this->faker->uuid, 'z', -1), EnumEntityType::DEVICE()), $payload, EnumTelemetryScope::SHARED_SCOPE());
    }
}
