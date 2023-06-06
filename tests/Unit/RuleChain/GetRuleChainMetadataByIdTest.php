<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetRuleChainMetadataByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $rootRuleChainId = thingsboard($tenantUser)->ruleChain()->getRuleChains(PaginationArguments::make(textSearch: 'Root Rule Chain'))->data()->first()->id->id;
        $rootRuleChain = thingsboard($tenantUser)->ruleChain()->getRuleChainMetadataById($rootRuleChainId);

        $this->assertInstanceOf(RuleChain::class, $rootRuleChain);
        $this->assertEquals($rootRuleChainId, $rootRuleChain->ruleChainId->id);
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($tenantUser)->ruleChain()->getRuleChainMetadataById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($tenantUser)->ruleChain()->getRuleChainMetadataById($this->faker->uuid);
    }
}
