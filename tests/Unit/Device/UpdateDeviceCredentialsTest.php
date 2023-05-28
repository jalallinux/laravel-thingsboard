<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\infrastructure\DeviceCredentials;
use JalalLinuX\Thingsboard\infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UpdateDeviceCredentialsTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->random();
        $deviceCredentials = thingsboard($tenantUser)->device()->getDeviceCredentialsByDeviceId($device->id->id);

        $originalId = $deviceCredentials->credentialsId();
        $newId = $this->faker->slug(3);
        $deviceCredentials = $deviceCredentials->setCredentialsId($newId);

        $deviceCredentials = thingsboard($tenantUser)->device()->updateDeviceCredentials($deviceCredentials);
        $this->assertInstanceOf(DeviceCredentials::class, $deviceCredentials);
        $this->assertEquals($newId, $deviceCredentials->credentialsId());

        thingsboard($tenantUser)->device()->updateDeviceCredentials($deviceCredentials->setCredentialsId($originalId));
    }
}
