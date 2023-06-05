<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantAssetInfosTest extends TestCase
{
    public function testGetTenantAssetInfosSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: 'default'))->data()->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $newAsset = thingsboard($tenantUser)->asset($attributes)->saveAsset();
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetSortProperty::cases(), [EnumAssetSortProperty::CUSTOMER_TITLE()]));
        $assets = thingsboard($tenantUser)->asset()->getTenantAssetInfos(
            PaginationArguments::make(sortProperty: $sortProperty), assetProfileId: $assetProfileId
        );

        $assets->data()->each(fn ($asset) => $this->assertInstanceOf(Asset::class, $asset));
        $result = $newAsset->deleteAsset();
        $this->assertTrue($result);

    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetSortProperty::cases(), [EnumAssetSortProperty::CUSTOMER_TITLE()]));
        $pagination = $this->randomPagination([$sortProperty]);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assets = thingsboard($tenantUser)->asset()->getTenantAssetInfos($pagination);

        $this->assertEquals($pagination->page, $assets->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $assets->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $assets->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assets->paginator()->getOptions()['sortProperty']);
    }
}
