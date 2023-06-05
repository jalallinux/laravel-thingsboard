<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnassignAssetFromCustomerTest extends TestCase
{
    public function testAssignAssetToCustomerSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: 'default'))->data()->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $newAsset = thingsboard($tenantUser)->asset($attributes)->saveAsset();
        $customerId = thingsboard($tenantUser)->customer()->getCustomers(PaginationArguments::make())->data()->first()->id->id;

        $asset = thingsboard($tenantUser)->asset()->assignAssetToCustomer($customerId, $newAsset->id->id);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals($customerId, $asset->customerId->id);

        $result = $asset->unassignAssetFromCustomer();
        $this->assertTrue($result);

        $result = $newAsset->deleteAsset();
        $this->assertTrue($result);
    }

    public function testInvalidCustomerUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->asset()->unassignAssetFromCustomer($uuid);
    }
}
