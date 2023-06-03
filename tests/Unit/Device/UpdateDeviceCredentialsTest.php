<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\DeviceCredentials;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UpdateDeviceCredentialsTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->random();
        $deviceCredentials = thingsboard($tenantUser)->device()->getDeviceCredentialsByDeviceId($device->id->id);

        $originalId = $deviceCredentials->credentialsId();

        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches("/32 character/");
        $deviceCredentials->setCredentialsId($this->faker->slug(30));

        $newId = substr($this->faker->slug, 0, 32);
        $deviceCredentials = $deviceCredentials->setCredentialsId($newId);

        $deviceCredentials = thingsboard($tenantUser)->device()->updateDeviceCredentials($deviceCredentials);
        $this->assertInstanceOf(DeviceCredentials::class, $deviceCredentials);
        $this->assertEquals($newId, $deviceCredentials->credentialsId());

        thingsboard($tenantUser)->device()->updateDeviceCredentials($deviceCredentials->setCredentialsId($originalId));
    }
}
