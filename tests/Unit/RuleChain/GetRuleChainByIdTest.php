<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetRuleChainByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $newRuleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();

        $ruleChain = thingsboard($tenantUser)->ruleChain()->getRuleChainById($newRuleChain->id->id);

        $this->assertEquals($newRuleChain->id->id, $ruleChain->id->id);

        $result = $newRuleChain->deleteRuleChain();

        $this->assertTrue($result);
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($tenantUser)->ruleChain()->getRuleChainById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($tenantUser)->ruleChain()->getRuleChainById($this->faker->uuid);
    }
}
