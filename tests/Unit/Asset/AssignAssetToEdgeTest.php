<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignAssetToEdgeTest extends TestCase
{
    public function testCorrectEdgeId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getDefaultAssetProfileInfo()->id->id;
        $newAsset = thingsboard($tenantUser)->asset()->saveAsset("- {$this->faker->sentence(3)}", $assetProfileId);
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $asset = thingsboard($tenantUser)->asset()->assignAssetToEdge($edge->id->id, $newAsset->id->id);
        $this->assertInstanceOf(Asset::class, $asset);

        $edge->deleteEdge();
        $newAsset->deleteAsset();
    }

    public function testInvalidEdgeUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getDefaultAssetProfileInfo()->id->id;
        $newAsset = thingsboard($tenantUser)->asset()->saveAsset("- {$this->faker->sentence(3)}", $assetProfileId);

        try {
            $this->expectExceptionCode(404);
            $this->expectExceptionMessageMatches("/{$uuid}/");
            thingsboard($tenantUser)->asset()->assignAssetToEdge($uuid, $newAsset->id->id);
        } finally {
            $newAsset->deleteAsset();
        }
    }
}
