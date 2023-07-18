<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AssetProfile;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\AssetProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SaveAssetProfileTest extends TestCase
{
    public function testCreateAssetProfileSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $assetProfile = thingsboard($tenantUser)->assetProfile($attributes)->saveAssetProfile();
        $assetProfile->deleteAssetProfile();

        $this->assertInstanceOf(AssetProfile::class, $assetProfile);
        $this->assertInstanceOf(Id::class, $assetProfile->id);
        $this->assertEquals($attributes['name'], $assetProfile->name);
    }

    public function testRequiredProperty()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($user)->assetProfile(Arr::except($attributes, 'name'))->saveAssetProfile();
    }

    public function testExistsName()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileName = thingsboard($tenantUser)->assetProfile()->getAssetProfileInfos(PaginationArguments::make())->collect()->first()->name;
        $attributes = [
            'name' => $assetProfileName,
        ];
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('/name/');
        thingsboard($tenantUser)->assetProfile($attributes)->saveAssetProfile();
    }
}
