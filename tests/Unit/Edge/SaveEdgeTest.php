<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Entities\Edge;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveEdgeTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edge = thingsboard($tenantUser)->edge()->saveEdge($name = "- {$this->faker->sentence(3)}");
        thingsboard($tenantUser)->edge()->deleteEdge($edge->id->id);

        $this->assertInstanceOf(Edge::class, $edge);
        $this->assertInstanceOf(Id::class, $edge->id);
        $this->assertEquals($name, $edge->name);
    }
}
