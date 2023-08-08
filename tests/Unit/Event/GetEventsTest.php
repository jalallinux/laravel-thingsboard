<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Event;

use Illuminate\Pagination\LengthAwarePaginator;
use JalalLinuX\Thingsboard\Entities\AuditLog;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEventSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEventType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEventsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumEventSortProperty::cases());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first();

        $events = thingsboard($tenantUser)->event([
            'body' => [
                'notEmpty' => false,
                'eventType' => 'STATS',
            ],
        ])->getEventsByEventFilter(PaginationArguments::make(sortProperty: $sortProperty), $device->id, $device->tenantId->id);
        $this->assertInstanceOf(LengthAwarePaginator::class, $events);
        $events->collect()->each(function ($event){
            $this->assertInstanceOf($event->tenantId, Id::class);
            $this->assertGreaterThan($event->type, EnumEventType::class);
            $this->assertInstanceOf($event->entityId, Id::class);
        });
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumEventSortProperty::cases());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect()->first();

        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/tenantId/');
        thingsboard($tenantUser)->event([
            'body' => [
                'notEmpty' => false,
                'eventType' => 'STATS',
            ],
        ])->getEventsByEventFilter(PaginationArguments::make(sortProperty: $sortProperty), $device->id, substr_replace($this->faker->uuid, 'z', -1));
    }
}
