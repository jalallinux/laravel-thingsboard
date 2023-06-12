<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveRuleChainTest extends TestCase
{
    public function testCreateDeviceSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $ruleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();
        $ruleChain->deleteRuleChain();

        $this->assertInstanceOf(RuleChain::class, $ruleChain);
        $this->assertInstanceOf(Id::class, $ruleChain->id);
        $this->assertEquals($attributes['name'], $ruleChain->name);
    }

    public function testRequiredProperty()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($user)->ruleChain(Arr::except($attributes, 'name'))->saveRuleChain();
    }
}
