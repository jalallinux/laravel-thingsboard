<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignAssetToCustomerTest extends TestCase
{
    public function testAssignAssetToCustomerSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetId = thingsboard($tenantUser)->asset()->getTenantAssets(PaginationArguments::make())->data()->first()->id->id;
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->data()->first()->id->id;

        $asset = thingsboard($tenantUser)->asset()->assignAssetToCustomer($customerId, $assetId);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals($customerId, $asset->customerId->id);

        $asset->unassignAssetFromCustomer();
    }

    public function testInvalidCustomerUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetId = thingsboard($tenantUser)->asset()->getTenantAssets(PaginationArguments::make())->data()->first()->id->id;

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->asset()->assignAssetToCustomer($uuid, $assetId);
    }
}
