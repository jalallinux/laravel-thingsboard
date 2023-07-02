<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerAssetInfosTest extends TestCase
{
    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumAssetSortProperty::class, 1, 20);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: 'default'))->collect()->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $newAsset = thingsboard($tenantUser)->asset($attributes)->saveAsset();

        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->collect()->random()->id->id;
        $asset = thingsboard($tenantUser)->asset()->assignAssetToCustomer($customerId, $newAsset->id->id);

        $assets = thingsboard($tenantUser)->asset()->getCustomerAssetInfos($pagination, $customerId);

        $assets->collect()->each(fn ($asset) => $this->assertInstanceOf(Asset::class, $asset));
        $asset->unassignAssetFromCustomer();

        $this->assertEquals($pagination->page, $assets->currentPage());
        $this->assertEquals($pagination->pageSize, $assets->perPage());
        $this->assertEquals($pagination->sortOrder, $assets->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assets->getOptions()['sortProperty']);

        $result = $newAsset->deleteAsset();
        $this->assertTrue($result);
    }
}
