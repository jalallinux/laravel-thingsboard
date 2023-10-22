<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edgeId = thingsboard($user)->edge()->getTenantEdges(PaginationArguments::make())->collect()->first()->id->id;

        $edge = thingsboard($user)->edge()->getEdgeById($edgeId);
        $this->assertEquals($edgeId, $edge->id->id);

        $edge = thingsboard($user)->edge(['id' => new Id($edgeId, EnumEntityType::DEVICE())])->getEdgeById();
        $this->assertEquals($edgeId, $edge->id->id);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->edge()->getEdgeById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->edge()->getEdgeById($this->faker->uuid);
    }
}
