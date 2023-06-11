<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\EntityRelation;

use JalalLinuX\Thingsboard\Entities\EntityRelation;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class GetRelationTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceCollection = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data();
        $device1Id = $deviceCollection->first()->id->id;
        $device2Id = $deviceCollection->last()->id->id;

        $relation = thingsboard($tenantUser)->entityRelation()->saveRelation(
            new Id($device1Id, EnumEntityType::DEVICE()),
            new Id($device2Id, EnumEntityType::DEVICE()),
            'Contains',
            'COMMON'
        );

        $this->assertTrue($relation);

        $getRelation = thingsboard($tenantUser)->entityRelation()->getRelation(
            new Id($device1Id, EnumEntityType::DEVICE()),
            'Contains',
            new Id($device2Id, EnumEntityType::DEVICE()),
            'COMMON'
        );

        $this->assertInstanceOf(EntityRelation::class, $getRelation);

        $result = thingsboard($tenantUser)->entityRelation()->deleteRelation(
            new Id($device1Id, EnumEntityType::DEVICE()),
            'Contains',
            new Id($device2Id, EnumEntityType::DEVICE()),
            'COMMON'
        );

        $this->assertTrue($result);
    }

    public function testWhenFromIsNull()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/from/');
        thingsboard($tenantUser)->entityRelation()->getRelation(
            null,
            'Contains',
            null,
            'COMMON'
        );
    }
}
