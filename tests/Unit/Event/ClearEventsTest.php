<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Event;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class ClearEventsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first();

        $events = thingsboard($tenantUser)->event([
            'body' => [
                'notEmpty' => true,
                'eventType' => 'STATS',
            ],
        ])->clearEvents($device->id);

        $this->assertIsBool($events);
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/id/');
        thingsboard($tenantUser)->event([
            'body' => [
                'notEmpty' => false,
                'eventType' => 'STATS',
            ],
        ])->clearEvents(new Id(substr_replace($this->faker->uuid, 'z', -1), EnumEntityType::DEVICE()));
    }
}
