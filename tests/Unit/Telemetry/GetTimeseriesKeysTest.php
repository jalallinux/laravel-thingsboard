<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Telemetry;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTimeseriesKeysTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetTimeseriesKeysSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $result = thingsboard($tenantUser)->telemetry()->getTimeseriesKeys(new Id($deviceId, EnumEntityType::DEVICE()));
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
        thingsboard($tenantUser)->telemetry()->getTimeseriesKeys(new Id($uuid, EnumEntityType::DEVICE()));
    }
}
