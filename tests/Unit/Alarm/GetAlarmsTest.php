<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Alarm;

use JalalLinuX\Thingsboard\Enums\EnumAlarmSeverityList;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAlarmsTest extends TestCase
{
    public function testGetAlarmsSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first()->id->id;
        $type = 'High-Temperature Alarm';
        $originator = new Id($deviceId, EnumEntityType::DEVICE());
        $severity = EnumAlarmSeverityList::CRITICAL();
        $newAlarm = thingsboard($tenantUser)->alarm()->saveAlarm($type, $originator, $severity);
        $alarms = thingsboard($tenantUser)->alarm()->getAlarms(PaginationArguments::make(), new Id($deviceId, EnumEntityType::DEVICE()));
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
        thingsboard($tenantUser)->alarm()->getAlarms(PaginationArguments::make(), new Id(substr_replace($this->faker->uuid, 'z', -1), EnumEntityType::DEVICE()));
    }
}
