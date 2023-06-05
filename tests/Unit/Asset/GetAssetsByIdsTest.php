<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAssetsByIdsTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: 'default'))->data()->first()->id->id;
        $attributes1 = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $attributes2 = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $newAsset1 = thingsboard($tenantUser)->asset($attributes1)->saveAsset();
        $newAsset2 = thingsboard($tenantUser)->asset($attributes2)->saveAsset();
        $assetIds = [$newAsset1->id->id, $newAsset2->id->id];
        $assets = thingsboard($tenantUser)->asset()->getAssetsByIds($assetIds);

        collect($assets)->each(function ($asset) use ($assetIds) {
            $this->assertInstanceOf(Asset::class, $asset);
            $this->assertTrue(in_array($asset->id->id, $assetIds));
        });

        $result = $newAsset1->deleteAsset();
        $this->assertTrue($result);

        $result = $newAsset2->deleteAsset();
        $this->assertTrue($result);
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assets = thingsboard($tenantUser)->asset()->getAssetsByIds([$this->faker->uuid, $this->faker->uuid]);

        $this->assertCount(0, $assets);
    }
}
