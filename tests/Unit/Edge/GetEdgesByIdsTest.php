<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Entities\Edge;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgesByIdsTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edgeIds = [
            thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id,
            thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id,
        ];
        $edges = thingsboard($tenantUser)->edge()->getEdgesByIds($edgeIds);

        collect($edges)->each(function ($edge) use ($edgeIds) {
            $this->assertInstanceOf(Edge::class, $edge);
            $this->assertTrue(in_array($edge->id->id, $edgeIds));
        });

        foreach ($edgeIds as $edgeId) {
            $result = thingsboard($tenantUser)->edge()->deleteEdge($edgeId);
            $this->assertTrue($result);
        }
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edges = thingsboard($tenantUser)->edge()->getEdgesByIds([$this->faker->uuid, $this->faker->uuid]);

        $this->assertCount(0, $edges);
    }
}
