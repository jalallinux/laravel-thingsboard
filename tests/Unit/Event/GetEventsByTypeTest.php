<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Event;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEventSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEventType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEventsByTypeTest extends TestCase
{
    public function testGetEventsByType()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumEventSortProperty::cases());
        $device = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->first();

        $events = thingsboard($tenantUser)->event([
            'body' => [
                'notEmpty' => false,
                'eventType' => 'STATS',
            ],
        ])->getEventsByType(PaginationArguments::make(sortProperty: $sortProperty), $device->id, EnumEventType::ERROR(), $device->tenantId->id);

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
         ])->getEventsByType(PaginationArguments::make(sortProperty: $sortProperty), $device->id, EnumEventType::LC_EVENT(), substr_replace($this->faker->uuid, 'z', -1));
     }
}
