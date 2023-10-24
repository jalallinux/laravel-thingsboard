<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AssetProfile;

use JalalLinuX\Thingsboard\Entities\AssetProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDefaultAssetProfileInfoTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfile = thingsboard($user)->assetProfile()->getDefaultAssetProfileInfo($this->faker->boolean);
        $assetProfile = thingsboard($user)->assetProfile()->getAssetProfileById($assetProfile->id->id);

        $this->assertInstanceOf(AssetProfile::class, $assetProfile);
        $this->assertInstanceOf(Id::class, $assetProfile->id);
        $this->assertTrue($assetProfile->default);
    }
}
