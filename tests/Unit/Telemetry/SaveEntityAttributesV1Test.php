<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveEntityAttributesV1Test extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCorrectPayload()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $scope = $this->faker->randomElement(array_diff(EnumTelemetryScope::cases(), [EnumTelemetryScope::CLIENT_SCOPE()]));
        $key = $this->faker->word();
        $value = $this->faker->word();
        $result = thingsboard($tenantUser)->telemetry()->saveEntityAttributesV1([$key => $value],EnumEntityType::DEVICE(), $deviceId, $scope);
        thingsboard($tenantUser)->telemetry()->deleteEntityAttributes(EnumEntityType::DEVICE(), $deviceId, $scope, [$key]);
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
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV1([], EnumEntityType::DEVICE(), $this->faker->uuid, EnumTelemetryScope::SERVER_SCOPE());
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidScope()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/scope/');
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV1(['a' => 'b'],  EnumEntityType::DEVICE(), $this->faker->uuid, EnumTelemetryScope::CLIENT_SCOPE());
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/entityId/');
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV1(['a' => 'b'],  EnumEntityType::DEVICE(), substr_replace($this->faker->uuid, 'z', -1), EnumTelemetryScope::SHARED_SCOPE());
    }
}
