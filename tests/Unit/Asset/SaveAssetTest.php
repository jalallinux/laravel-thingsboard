<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\Asset;
use JalalLinuX\Thingsboard\Entities\Device;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveAssetTest extends TestCase
{
    public function testCreateAssetSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: "default"))->data()->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE())
        ];
        $asset = thingsboard($tenantUser)->asset($attributes)->saveAsset();

        $asset->deleteAsset($asset->id->id);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertInstanceOf(Id::class, $asset->id);
        $this->assertEquals($attributes['name'], $asset->name);
        $this->assertEquals($attributes['assetProfileId']->id, $asset->assetProfileId->id);
    }

    public function testRequiredProperty()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'assetProfileId' => new Id($this->faker->uuid, EnumEntityType::ASSET_PROFILE())
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($tenantUser)->asset($attributes)->saveAsset();
    }

    public function testExistsName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $asset = thingsboard($tenantUser)->asset()->getTenantAssets(PaginationArguments::make())->data()->first();
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: "default"))->data()->first()->id->id;
        $attributes = [
            'name' => $asset->name,
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE())
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($tenantUser)->asset($attributes)->saveAsset();
    }
}
