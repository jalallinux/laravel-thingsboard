<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\EntityRelation;

use JalalLinuX\Thingsboard\Entities\EntityRelation;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class FindInfoByFromTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceCollection = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->data();
        $device1Id = $deviceCollection->first()->id->id;
        $device2Id = $deviceCollection->last()->id->id;
        $result = thingsboard($tenantUser)->entityRelation()->saveRelation(
            new Id($device1Id, EnumEntityType::DEVICE()),
            new Id($device2Id, EnumEntityType::DEVICE()),
            'Contains',
            'COMMON'
        );

        $this->assertTrue($result);

        $findEntities = thingsboard($tenantUser)->entityRelation()->findInfoByFrom(new Id($device1Id, EnumEntityType::DEVICE()), 'COMMON');

        foreach ($findEntities as $entity) {
            $this->assertInstanceOf(EntityRelation::class, $entity);
        }

        $result = thingsboard($tenantUser)->entityRelation()->deleteRelations(
            new Id($device1Id, EnumEntityType::DEVICE())
        );

        $this->assertTrue($result);
    }

    public function testWhenFromIsNull()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/to/');
        thingsboard($tenantUser)->entityRelation()->findInfoByTo();
    }
}
