<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveDeviceAttributesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testCorrectPayload()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $scope = $this->faker->randomElement(EnumTelemetryScope::cases());
        $key = $this->faker->word();
        $value = $this->faker->word();
        $result = thingsboard($tenantUser)->telemetry()->saveDeviceAttributes([$key => $value], $scope, $deviceId);
        thingsboard($tenantUser)->telemetry()->deleteDeviceAttributes($scope, [$key], $deviceId);
        $this->assertTrue($result);
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidPayload()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches("/payload/");
        thingsboard($tenantUser)->telemetry()->saveDeviceAttributes([], EnumTelemetryScope::SERVER_SCOPE(), Str::uuid());
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidScope()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches("/scope/");
        thingsboard($tenantUser)->telemetry()->saveDeviceAttributes(['a' => 'b'], EnumTelemetryScope::CLIENT_SCOPE(), Str::uuid());
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches("/deviceId/");
        thingsboard($tenantUser)->telemetry()->saveDeviceAttributes(['a' => 'b'], EnumTelemetryScope::SHARED_SCOPE(), substr_replace($this->faker->uuid, 'z', -1));
    }
}
