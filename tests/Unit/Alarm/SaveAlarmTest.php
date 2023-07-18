<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Alarm;

use JalalLinuX\Thingsboard\Entities\Alarm;
use JalalLinuX\Thingsboard\Enums\EnumAlarmSeverityList;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveAlarmTest extends TestCase
{
    public function testCreateAssetSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $attributes = [
            'type' => 'High-Temperature Alarm',
            'originator' => new Id($deviceId, EnumEntityType::DEVICE()),
            'severity' => EnumAlarmSeverityList::CRITICAL(),
        ];
        $newAlarm = thingsboard($tenantUser)->alarm($attributes)->saveAlarm();

        $this->assertInstanceOf(Alarm::class, $newAlarm);
        $this->assertInstanceOf(Id::class, $newAlarm->id);
        $this->assertEquals($attributes['type'], $newAlarm->type);

        $result = $newAlarm->deleteAlarm();
        $this->assertTrue($result);
    }

    public function testRequiredProperty()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceId = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first()->id->id;
        $attributes = [
            'originator' => new Id($deviceId, EnumEntityType::DEVICE()),
            'severity' => EnumAlarmSeverityList::CRITICAL(),
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/type/');
        $alarm = thingsboard($tenantUser)->alarm($attributes)->saveAlarm();

        $result = $alarm->deleteAlarm();
        $this->assertTrue($result);
    }

    public function testNotExistsAssetProfileUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'type' => 'High-Temperature Alarm',
            'originator' => new Id($this->faker->uuid, EnumEntityType::DEVICE()),
            'severity' => EnumAlarmSeverityList::CRITICAL(),
        ];
        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches('/not found/');
        thingsboard($tenantUser)->alarm($attributes)->saveAlarm();
    }
}
