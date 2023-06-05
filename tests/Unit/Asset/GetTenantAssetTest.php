<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetTenantAssetTest extends TestCase
{
    public function testExistName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($tenantUser)->assetProfile()->getAssetProfiles(PaginationArguments::make(textSearch: 'default'))->data()->first()->id->id;
        $attributes = [
            'name' => $this->faker->sentence(3),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ];
        $newAsset = thingsboard($tenantUser)->asset($attributes)->saveAsset();

        $asset = thingsboard($tenantUser)->asset()->getTenantAsset($newAsset->name);
        $this->assertEquals($newAsset->id->id, $asset->id->id);
        $this->assertEquals($newAsset->name, $asset->name);

        $result = $newAsset->deleteAsset();

        $this->assertTrue($result);
    }

    public function testNonExistName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        thingsboard($tenantUser)->asset()->getTenantAsset($this->faker->name);
    }
}
