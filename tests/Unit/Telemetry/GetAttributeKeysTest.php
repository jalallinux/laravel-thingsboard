<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAttributeKeysTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetAttributeKeysSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $result = thingsboard($tenantUser)->telemetry()->getAttributeKeys(new Id($deviceId, EnumEntityType::DEVICE()));
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
        thingsboard($tenantUser)->telemetry()->getAttributeKeys(new Id($uuid, EnumEntityType::DEVICE()));
    }
}
