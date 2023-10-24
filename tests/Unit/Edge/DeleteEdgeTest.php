<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteEdgeTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $result = thingsboard($tenantUser)->edge()->deleteEdge($edge->id->id);
        $this->assertTrue($result);
    }
}
