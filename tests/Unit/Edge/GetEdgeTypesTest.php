<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Type;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeTypesTest extends TestCase
{
    public function testStructure()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edgeId = thingsboard($user)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        $types = thingsboard($user)->edge()->getEdgeTypes();
        thingsboard($user)->edge()->deleteEdge($edgeId);

        self::assertIsArray($types);
        collect($types)->each(fn ($type) => self::assertInstanceOf(Type::class, $type));
    }
}
