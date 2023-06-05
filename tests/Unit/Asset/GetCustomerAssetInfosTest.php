<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetCustomerAssetInfosTest extends TestCase
{
    public function testPaginationData()
    {
        $pagination = $this->randomPagination(EnumAssetSortProperty::class, 1, 20);
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $assetId = thingsboard($tenantUser)->asset()->getTenantAssets(PaginationArguments::make())->data()->random()->id->id;
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->data()->random()->id->id;
        $asset = thingsboard($tenantUser)->asset()->assignAssetToCustomer($customerId, $assetId);

        $assets = thingsboard($tenantUser)->asset()->getCustomerAssetInfos($pagination, $customerId);

        $assets->data()->each(fn ($asset) => $this->assertInstanceOf(Asset::class, $asset));
        $asset->unassignAssetFromCustomer();

        $this->assertEquals($pagination->page, $assets->paginator()->currentPage());
        $this->assertEquals($pagination->pageSize, $assets->paginator()->perPage());
        $this->assertEquals($pagination->sortOrder, $assets->paginator()->getOptions()['sortOrder']);
        $this->assertEquals($pagination->sortProperty, $assets->paginator()->getOptions()['sortProperty']);
    }
}
