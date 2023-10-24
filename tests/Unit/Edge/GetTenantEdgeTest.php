<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantEdgeTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $newEdge = thingsboard($user)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $edge = thingsboard($user)->edge()->getTenantEdge($newEdge->name);
        $this->assertEquals($newEdge->name, $edge->name);

        thingsboard($user)->edge()->deleteEdge($newEdge->id->id);
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->edge()->getTenantEdge($this->faker->word);
    }
}
