<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\Device;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\Type;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetDeviceTypesTest extends TestCase
{
    public function testSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $types = thingsboard($tenantUser)->device()->getDeviceTypes();
        $deviceTypes = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data()->pluck('type')->unique()->values();

        $this->assertIsArray($types);

        collect($types)->each(function ($type) use ($deviceTypes) {
            $this->assertInstanceOf(Type::class, $type);
            $this->assertTrue($deviceTypes->contains($type->type()));
        });
    }
}
