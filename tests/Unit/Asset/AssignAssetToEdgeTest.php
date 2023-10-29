<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class AssignAssetToEdgeTest extends TestCase
{
    public function testCorrectEdgeId()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetId = thingsboard($tenantUser)->asset()->getTenantAssetInfos(PaginationArguments::make())->collect()->first()->id->id;
        $edge = thingsboard($tenantUser)->edge()->saveEdge("- {$this->faker->sentence(3)}");

        $asset = thingsboard($tenantUser)->asset()->assignAssetToEdge($edge->id->id, $assetId);
        $this->assertInstanceOf(Asset::class, $asset);

        $edge->deleteEdge();
    }

    public function testInvalidEdgeUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetId = thingsboard($tenantUser)->asset()->getTenantAssetInfos(PaginationArguments::make())->collect()->first()->id->id;

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->asset()->assignAssetToEdge($uuid, $assetId);
    }
}
