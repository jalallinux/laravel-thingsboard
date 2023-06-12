<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AssetProfile;

use JalalLinuX\Thingsboard\Entities\AssetProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class DeleteAssetProfileTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfile = thingsboard($tenantUser)->assetProfile(['name' => $this->faker->sentence(3)])->saveAssetProfile();

        $this->assertInstanceOf(AssetProfile::class, $assetProfile);
        $this->assertInstanceOf(Id::class, $assetProfile->id);

        $result = $assetProfile->deleteAssetProfile();
        self::assertTrue($result);
    }

    public function testNonExistUuid()
    {
        $uuid = $this->faker->uuid;
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        $this->expectExceptionMessageMatches("/{$uuid}/");
        thingsboard($tenantUser)->assetProfile()->deleteAssetProfile($uuid);
    }
}
