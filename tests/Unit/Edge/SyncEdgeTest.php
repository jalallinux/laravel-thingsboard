<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SyncEdgeTest extends TestCase
{
    public function testNotConnect()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edgeId = thingsboard($user)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        try {
            $this->expectExceptionCode(500);
            $this->expectException(Exception::class);
            $result = thingsboard($user)->edge()->syncEdge($edgeId);
            self::assertIsArray($result);
        } finally {
            thingsboard($user)->edge()->deleteEdge($edgeId);
        }
    }
}
