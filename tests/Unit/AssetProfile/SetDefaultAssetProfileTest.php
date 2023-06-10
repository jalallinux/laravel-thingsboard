<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\AssetProfile;

use JalalLinuX\Thingsboard\Entities\AssetProfile;
use JalalLinuX\Thingsboard\Enums\EnumAssetProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class SetDefaultAssetProfileTest extends TestCase
{
    public function testExistUuid()
    {
        $user = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $originalDefaultAssetProfileId = thingsboard($user)->assetProfile()->getDefaultAssetProfileInfo()->id->id;

        $assetProfileId = thingsboard($user)->assetProfile()->getAssetProfiles(
            PaginationArguments::make(sortProperty: EnumAssetProfileSortProperty::IS_DEFAULT(), sortOrder: EnumSortOrder::ASC())
        )->data()->first()->id->id;

        $assetProfile = thingsboard($user)->assetProfile()->setDefaultAssetProfile($assetProfileId);
        $assetProfile = thingsboard($user)->assetProfile()->getAssetProfileById($assetProfile->id->id);
        thingsboard($user)->assetProfile()->setDefaultAssetProfile($originalDefaultAssetProfileId);

        $this->assertInstanceOf(AssetProfile::class, $assetProfile);
        $this->assertInstanceOf(Id::class, $assetProfile->id);
        $this->assertTrue($assetProfile->default);
    }
}
