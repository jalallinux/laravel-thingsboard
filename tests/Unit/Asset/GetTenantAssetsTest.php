<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantAssetsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: 'default'))->collect()->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $newAsset = thingsboard($tenantUser)->asset($attributes)->saveAsset();

        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetSortProperty::cases(), [EnumAssetSortProperty::CUSTOMER_TITLE()]));
        $assets = thingsboard($tenantUser)->asset()->getTenantAssets(
            PaginationArguments::make(sortProperty: $sortProperty)
        );

        $assets->collect()->each(fn ($asset) => $this->assertInstanceOf(Asset::class, $asset));

        $result = $newAsset->deleteAsset();
        $this->assertTrue($result);

    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetSortProperty::cases(), [EnumAssetSortProperty::CUSTOMER_TITLE()]));
        $pagination = $this->randomPagination([$sortProperty]);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assets = thingsboard($tenantUser)->asset()->getTenantAssets($pagination);

        $this->assertEquals($pagination->page, $assets->currentPage());
        $this->assertEquals($pagination->pageSize, $assets->perPage());
        $this->assertEquals($pagination->sortOrder, $assets->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assets->getOptions()['sortProperty']);
    }
}
