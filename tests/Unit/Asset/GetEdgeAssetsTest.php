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
        $assetId = thingsboard($tenantUser)->asset()->getTenantAssetInfos(PaginationArguments::make())->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        thingsboard($tenantUser)->asset()->assignAssetToEdge($edge->id->id, $assetId);

        $assets = thingsboard($tenantUser)->asset()->getEdgeAssets(PaginationArguments::make(), $edge->id->id);
        $edge->deleteEdge();

        $assets->each(function ($asset) use ($assetId) {
            $this->assertInstanceOf(Asset::class, $asset);
            $this->assertTrue($asset->id->id == $assetId);
        });
    }
}
