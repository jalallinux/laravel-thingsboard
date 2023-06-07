<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetLatestRuleNodeDebugInputTest extends TestCase
{
    public function testGetLatestRuleNodeDebugInputTestSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        /** @var RuleChain $rootRuleChain */
        $rootRuleChain = thingsboard($tenantUser)->ruleChain()->getRuleChains(PaginationArguments::make(textSearch: "Root Rule Chain"))->data()->first();
        $metadata = $rootRuleChain->getRuleChainMetadataById();
        $result = thingsboard($tenantUser)->ruleChain()->getLatestRuleNodeDebugInput($metadata->getAttribute('nodes')[0]['id']['id']);
        if(! is_null($result)){
            $this->assertIsArray($result);
            $this->assertArrayHasKey('type', $result);
            $this->assertEquals('IN', $result['type']);
        }else{
            $this->assertNull($result);
        }
    }
}
