<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Asset;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetAssetTypesTest extends TestCase
{
    public function testGetAssetTypesSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $types = thingsboard($tenantUser)->asset()->getAssetTypes();

        $this->assertIsArray($types);

        array_map(fn ($type) => $this->assertArrayHasKey('type', $type->toArray()), $types);
    }
}
