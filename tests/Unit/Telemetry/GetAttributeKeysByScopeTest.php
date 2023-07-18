<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumTelemetryScope;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAttributeKeysByScopeTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetAttributeKeysSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $scope = $this->faker->randomElement(EnumTelemetryScope::cases());
        $result = thingsboard($tenantUser)->telemetry()->getAttributeKeysByScope(new Id($deviceId, EnumEntityType::DEVICE()), $scope);
        self::assertIsArray($result);
    }

    /**
     * @throws \Throwable
     */
    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $scope = $this->faker->randomElement(EnumTelemetryScope::cases());
        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->telemetry()->getAttributeKeysByScope(new Id($uuid, EnumEntityType::DEVICE()), $scope);
    }
}
