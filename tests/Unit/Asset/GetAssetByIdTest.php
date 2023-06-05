<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Exceptions\Exception;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAssetByIdTest extends TestCase
{
    public function testExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $assetId = thingsboard($tenantUser)->asset()->getTenantAssets(
            PaginationArguments::make()
        )->data()->first()->id->id;

        $asset = thingsboard($tenantUser)->asset()->getAssetById($assetId);
        $this->assertEquals($assetId, $asset->id->id);
    }

    public function testInvalidUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectException(Exception::class);
        $this->expectExceptionCode(500);
        thingsboard($tenantUser)->asset()->getAssetById(substr_replace($this->faker->uuid, 'z', -1));
    }

    public function testNonExistUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(404);
        thingsboard($tenantUser)->asset()->getAssetById($this->faker->uuid);
    }
}
