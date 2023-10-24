<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Edge;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SetEdgeRootRuleChainTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $ruleChainId = thingsboard($tenantUser)->ruleChain()->getRuleChains(PaginationArguments::make())->collect()->random()->id->id;
        $edgeId = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}")->id->id;

        $edge = thingsboard($tenantUser)->edge()->setEdgeRootRuleChain($ruleChainId, $edgeId);
        thingsboard($tenantUser)->edge()->deleteEdge($edgeId);

        $this->assertEquals($edgeId, $edge->id->id);

        $this->assertEquals(EnumEntityType::RULE_CHAIN()->value, $edge->rootRuleChainId->entityType);
        $this->assertEquals($ruleChainId, $edge->rootRuleChainId->id);
    }
}
