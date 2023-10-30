<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignRuleChainToEdgeTest extends TestCase
{
    public function testInCorrectRuleChainId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $ruleChainId = thingsboard($tenantUser)->ruleChain()->getRuleChains(PaginationArguments::make())->collect()->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        try {
            $this->expectExceptionCode(400);
            $this->expectExceptionMessageMatches('/non EDGE/');
            thingsboard($tenantUser)->ruleChain()->assignRuleChainToEdge($edge->id->id, $ruleChainId);
        } finally {
            $edge->deleteEdge();
        }
    }

    public function testInvalidEdgeUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $ruleChainId = thingsboard($tenantUser)->ruleChain()->getRuleChains(PaginationArguments::make())->collect()->first()->id->id;

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->ruleChain()->assignRuleChainToEdge($uuid, $ruleChainId);
    }
}
