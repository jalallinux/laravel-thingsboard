<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AssetProfile;

use JalalLinuX\Thingsboard\Entities\AssetProfile;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAssetProfileByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetProfileId = thingsboard($user)->assetProfile()->getAssetProfiles(
            PaginationArguments::make()
        )->collect()->first()->id->id;

        $assetProfile = thingsboard($user)->assetProfile()->getAssetProfileById($assetProfileId);
        $this->assertEquals($assetProfileId, $assetProfile->id->id);

        $assetProfile = thingsboard($user)->assetProfile(['id' => new Id($assetProfileId, EnumEntityType::DEVICE())])->getAssetProfileById();
        $this->assertEquals($assetProfileId, $assetProfile->id->id);
        $this->assertInstanceOf(AssetProfile::class, $assetProfile);
    }

    public function testInvalidUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($user)->assetProfile()->getAssetProfileById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($user)->assetProfile()->getAssetProfileById($this->faker->uuid);
    }
}
