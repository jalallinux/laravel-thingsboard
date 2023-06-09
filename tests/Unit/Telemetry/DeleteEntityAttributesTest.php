<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteEntityAttributesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testDeleteEntityAttributesSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $key = $this->faker->word();
        $value = $this->faker->word();
        $payload = [$key => $value];
        $scope = $this->faker->randomElement(array_diff(EnumTelemetryScope::cases(), [EnumTelemetryScope::CLIENT_SCOPE()]));
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        thingsboard($tenantUser)->telemetry()->saveEntityAttributesV1(new Id($deviceId, EnumEntityType::DEVICE()), $payload, EnumTelemetryScope::SHARED_SCOPE());
        $result = thingsboard($tenantUser)->telemetry()->deleteEntityAttributes(new Id($deviceId, EnumEntityType::DEVICE()), $scope, [$key]);
        self::assertTrue($result);
    }

    /**
     * @throws \Throwable
     */
    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;

        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $key = $this->faker->word();
        $scope = $this->faker->randomElement(array_diff(EnumTelemetryScope::cases(), [EnumTelemetryScope::CLIENT_SCOPE()]));
        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->telemetry()->deleteEntityAttributes(new Id($uuid, EnumEntityType::DEVICE()), $scope, [$key]);
    }
}
