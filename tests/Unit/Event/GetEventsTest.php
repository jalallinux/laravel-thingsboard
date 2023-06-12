<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Event;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEventSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEventsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumEventSortProperty::cases());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first();

        $events = thingsboard($tenantUser)->event([
            'body' => [
                'notEmpty' => false,
                'eventType' => 'STATS',
            ],
        ])->getEventsByEventFilter(PaginationArguments::make(sortProperty: $sortProperty), $device->id, $device->tenantId->id);

        $this->assertIsArray($events);
        $this->assertArrayHasKey('data', $events);
    }

     public function testInvalidUuid()
     {
         $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
         $sortProperty = $this->faker->randomElement(EnumEventSortProperty::cases());
         $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first();

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
