<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\Markdown;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeDockerInstallInstructionsTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $edgeId = thingsboard($user)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        $instructions = thingsboard($user)->edge()->getEdgeDockerInstallInstructions($edgeId);
        thingsboard($user)->edge()->deleteEdge($edgeId);

        self::assertInstanceOf(Markdown::class, $instructions);
        self::assertIsString($instructions->markdown());
        self::assertIsString($instructions->inlineMarkdown());
        self::assertIsString($instructions->toHtml());
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->edge()->getEdgeDockerInstallInstructions(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->edge()->getEdgeDockerInstallInstructions($this->faker->uuid);
    }
}
