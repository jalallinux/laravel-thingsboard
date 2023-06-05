<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAssetsByIdsTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetIds = thingsboard($tenantUser)->asset()->getTenantAssets(PaginationArguments::make())->data()->pluck('id.id')->toArray();
        $assetIds = $this->faker->randomElements($assetIds, $count = $this->faker->numberBetween(1, 2));
        $assets = thingsboard($tenantUser)->asset()->getAssetsByIds($assetIds);

        $this->assertCount($count, $assets);
        collect($assets)->each(function ($asset) use ($assetIds) {
            $this->assertInstanceOf(Asset::class, $asset);
            $this->assertTrue(in_array($asset->id->id, $assetIds));
        });
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assets = thingsboard($tenantUser)->asset()->getAssetsByIds([$this->faker->uuid, $this->faker->uuid]);

        $this->assertCount(0, $assets);
    }
}
