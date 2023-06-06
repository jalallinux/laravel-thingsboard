<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteRuleChainTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $ruleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();

        $this->assertInstanceOf(RuleChain::class, $ruleChain);
        $this->assertInstanceOf(Id::class, $ruleChain->id);

        $result = $ruleChain->deleteRuleChain();
        self::assertTrue($result);
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->ruleChain()->deleteRuleChain($uuid);
    }
}
