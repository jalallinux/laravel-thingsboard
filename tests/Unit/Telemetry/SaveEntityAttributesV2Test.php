<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveEntityAttributesV2Test extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCorrectPayload()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $scope = $this->faker->randomElement(array_diff(EnumTelemetryScope::cases(), [EnumTelemetryScope::CLIENT_SCOPE()]));
        $key = $this->faker->word();
        $value = $this->faker->word();
        $result = thingsboard($tenantUser)->telemetry()->saveEntityAttributesV2(new Id($deviceId, EnumEntityType::DEVICE()), [$key => $value], $scope);
        thingsboard($tenantUser)->telemetry()->deleteEntityAttributes(new Id($deviceId, EnumEntityType::DEVICE()), $scope, [$key]);
        $this->assertTrue($result);
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidPayload()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/payload/');
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV2(new Id($this->faker->uuid, EnumEntityType::DEVICE()), [], EnumTelemetryScope::SERVER_SCOPE());
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidScope()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/scope/');
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV2(new Id($this->faker->uuid, EnumEntityType::DEVICE()), ['a' => 'b'], EnumTelemetryScope::CLIENT_SCOPE());
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV2(new Id(substr_replace($this->faker->uuid, 'z', -1), EnumEntityType::DEVICE()), ['a' => 'b'], EnumTelemetryScope::SHARED_SCOPE());
    }
}
