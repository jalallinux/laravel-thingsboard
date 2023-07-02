<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AssetProfile;

use JalalLinuX\Thingsboard\Entities\AssetProfile;
use JalalLinuX\Thingsboard\Enums\EnumAssetProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAssetProfileInfosTest extends TestCase
{
    public function testTextSearch()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetProfileSortProperty::cases()));
        $assetProfiles = thingsboard($tenantUser)->assetProfile()->getAssetProfileInfos(
            PaginationArguments::make(sortProperty: $sortProperty)
        );

        $assetProfiles->collect()->each(fn ($assetProfile) => $this->assertInstanceOf(AssetProfile::class, $assetProfile));
    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetProfileSortProperty::cases()));
        $pagination = $this->randomPagination([$sortProperty]);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assetProfiles = thingsboard($tenantUser)->assetProfile()->getAssetProfileInfos($pagination);

        $this->assertEquals($pagination->page, $assetProfiles->currentPage());
        $this->assertEquals($pagination->pageSize, $assetProfiles->perPage());
        $this->assertEquals($pagination->sortOrder, $assetProfiles->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assetProfiles->getOptions()['sortProperty']);
    }
}
