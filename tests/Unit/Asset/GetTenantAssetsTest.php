<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAssetProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantAssetsTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetSortProperty::cases()));
        $assets = thingsboard($tenantUser)->asset()->getTenantAssets(
            PaginationArguments::make(sortProperty: $sortProperty)
        );

        $assets->data()->each(fn ($asset) => $this->assertInstanceOf(Asset::class, $asset));
    }

    public function testPaginationData()
    {
        $sortProperty = $this->faker->randomElement(array_diff(EnumAssetSortProperty::cases()));
        $pagination = $this->randomPagination([$sortProperty]);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assets = thingsboard($tenantUser)->asset()->getTenantAssets($pagination);

        $this->assertEquals($pagination->page, $assets->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $assets->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $assets->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assets->paginator()->getOptions()['sortProperty']);
    }
}
