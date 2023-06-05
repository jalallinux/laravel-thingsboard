<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEntityAttributesTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetAttributeKeysSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $result = thingsboard($tenantUser)->telemetry()->getEntityAttributes(new Id($deviceId, EnumEntityType::DEVICE()));
        self::assertIsArray($result);
    }

    public function testGetAttributesWithRealKeysSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $attributesKeys = thingsboard($tenantUser)->telemetry()->getAttributeKeys(new Id($deviceId, EnumEntityType::DEVICE()));
        $result = thingsboard($tenantUser)->telemetry()->getEntityAttributes(new Id($deviceId, EnumEntityType::DEVICE()), $attributesKeys);
        self::assertIsArray($result);
    }

    /**
     * @throws \Throwable
     */
    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->telemetry()->getEntityAttributes(new Id($uuid, EnumEntityType::DEVICE()));
    }
}
