<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UnAssignAssetFromEdgeTest extends TestCase
{
    public function testCorrectAssetId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getDefaultAssetProfileInfo()->id->id;
        $newAsset = thingsboard($tenantUser)->asset()->saveAsset("- {$this->faker->sentence(3)}", $assetProfileId);
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $asset = thingsboard($tenantUser)->asset()->assignAssetToEdge($edge->id->id, $newAsset->id->id);
        $this->assertInstanceOf(Asset::class, $asset);

        $asset = thingsboard($tenantUser)->asset()->unassignAssetFromEdge($edge->id->id, $newAsset->id->id);
        $this->assertInstanceOf(Asset::class, $asset);

        $edge->deleteEdge();
        $newAsset->deleteAsset();
    }
}
