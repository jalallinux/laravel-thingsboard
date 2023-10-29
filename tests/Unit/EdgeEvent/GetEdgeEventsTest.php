<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\EdgeEvent;

use JalalLinuX\Thingsboard\Entities\EdgeEvent;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeEventsTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");
        $events = thingsboard($tenantUser)->edgeEvent()->getEdgeEvents(PaginationArguments::make(), $edge->id->id);

        $events->each(function ($event) use ($edge) {
            $this->assertInstanceOf(EdgeEvent::class, $event);
            $this->assertEquals($edge->id->id, $event->edgeId->id);
        });
    }
}
