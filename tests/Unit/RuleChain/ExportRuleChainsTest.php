<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class ExportRuleChainsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $newRuleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();

        $ruleChains = thingsboard($tenantUser)->ruleChain()->exportRuleChains($this->faker->numberBetween(1, 10));

        foreach ($ruleChains as $ruleChain) {
            $this->assertInstanceOf(RuleChain::class, $ruleChain);
        }

        $result = $newRuleChain->deleteRuleChain();
        $this->assertTrue($result);
    }
}
