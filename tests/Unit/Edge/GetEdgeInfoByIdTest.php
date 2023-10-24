<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeInfoByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edgeId = thingsboard($user)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        $edge = thingsboard($user)->edge()->getEdgeInfoById($edgeId);
        $this->assertEquals($edgeId, $edge->id->id);

        $edge = thingsboard($user)->edge(['id' => new Id($edgeId, EnumEntityType::DEVICE())])->getEdgeById();
        $this->assertEquals($edgeId, $edge->id->id);
        thingsboard($user)->edge()->deleteEdge($edgeId);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->edge()->getEdgeInfoById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->edge()->getEdgeInfoById($this->faker->uuid);
    }
}
