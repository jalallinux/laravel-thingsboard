<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SetRootRuleChainTest extends TestCase
{
    public function testExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $rootRuleChainId = thingsboard($tenantUser)->ruleChain()->getRuleChains(PaginationArguments::make())->data()->filter(fn ($ruleChain) => $ruleChain->root)->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $newRuleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();

        $ruleChain = thingsboard($tenantUser)->ruleChain()->setRootRuleChain($newRuleChain->id->id);
        $this->assertEquals($newRuleChain->id->id, $ruleChain->id->id);
        $this->assertTrue($ruleChain->root);

        thingsboard($tenantUser)->ruleChain()->setRootRuleChain($rootRuleChainId);

        $result = $newRuleChain->deleteRuleChain();
        $this->assertTrue($result);
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($tenantUser)->ruleChain()->setRootRuleChain(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($tenantUser)->ruleChain()->setRootRuleChain($this->faker->uuid);
    }
}
