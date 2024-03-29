<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Alarm;

use JalalLinuX\Thingsboard\Enums\EnumAlarmSeverityList;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class getAlarmsV2Test extends TestCase
{
    public function testGetAlarmsSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $attributes = [
            'type' => 'High-Temperature Alarm',
            'originator' => new Id($deviceId, EnumEntityType::DEVICE()),
            'severity' => EnumAlarmSeverityList::CRITICAL(),
        ];
        $newAlarm = thingsboard($tenantUser)->alarm($attributes)->saveAlarm();
        $alarms = thingsboard($tenantUser)->alarm()->getAlarmsV2(PaginationArguments::make(), new Id($deviceId, EnumEntityType::DEVICE()));
        $this->assertIsArray($alarms);
        $this->assertArrayHasKey('data', $alarms);

        $result = $newAlarm->deleteAlarm();

        $this->assertTrue($result);
    }

    public function testNonExistName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->alarm()->getAlarmsV2(PaginationArguments::make(), new Id(substr_replace($this->faker->uuid, 'z', -1), EnumEntityType::DEVICE()));
    }
}
