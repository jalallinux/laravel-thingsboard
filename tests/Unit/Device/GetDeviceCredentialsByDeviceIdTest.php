<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceCredentialsType;
use JalalLinuX\Thingsboard\Infrastructure\DeviceCredentials;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceCredentialsByDeviceIdTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->random()->id->id;
        $deviceCredentials = thingsboard($tenantUser)->device()->getDeviceCredentialsByDeviceId($deviceId);

        $this->assertInstanceOf(DeviceCredentials::class, $deviceCredentials);
        $this->assertInstanceOf(Carbon::class, $deviceCredentials->createdTime());
        $this->assertInstanceOf(EnumDeviceCredentialsType::class, $deviceCredentials->credentialsType());
        $this->assertInstanceOf(Id::class, $deviceCredentials->deviceId());
    }

    public function testNonExistsUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $uuid = $this->faker->uuid;

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->device()->getDeviceCredentialsByDeviceId($uuid);
    }
}
