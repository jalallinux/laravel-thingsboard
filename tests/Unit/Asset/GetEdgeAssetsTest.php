<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetEdgeAssetsTest extends TestCase
{
    public function testStructure()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getDefaultAssetProfileInfo()->id->id;
        $newAsset = thingsboard($tenantUser)->asset()->saveAsset("- {$this->faker->sentence(3)}", $assetProfileId);
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        thingsboard($tenantUser)->asset()->assignAssetToEdge($edge->id->id, $newAsset->id->id);

        $assets = thingsboard($tenantUser)->asset()->getEdgeAssets(PaginationArguments::make(), $edge->id->id);
        $edge->deleteEdge();
        $newAsset->deleteAsset();

        $assets->each(function ($asset) use ($newAsset) {
            $this->assertInstanceOf(Asset::class, $asset);
            $this->assertTrue($asset->id->id == $newAsset->id->id);
        });
    }
}
