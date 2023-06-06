<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumRuleChainSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetRuleChainsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $newRuleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();

        $sortProperty = $this->faker->randomElement(EnumRuleChainSortProperty::cases());
        $ruleChains = thingsboard($tenantUser)->ruleChain()->getRuleChains(
            PaginationArguments::make(sortProperty: $sortProperty)
        );

        $ruleChains->data()->each(fn ($ruleCHain) => $this->assertInstanceOf(RuleChain::class, $ruleCHain));

        $result = $newRuleChain->deleteRuleChain();
        $this->assertTrue($result);

    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(EnumRuleChainSortProperty::cases());
        $pagination = $this->randomPagination([$sortProperty]);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assets = thingsboard($tenantUser)->ruleChain()->getRuleChains($pagination);

        $this->assertEquals($pagination->page, $assets->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $assets->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $assets->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assets->paginator()->getOptions()['sortProperty']);
    }
}
