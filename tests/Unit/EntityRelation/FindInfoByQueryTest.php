<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\EntityRelation;

use JalalLinuX\Thingsboard\Entities\EntityRelation;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class FindInfoByQueryTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $deviceCollection = thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->collect();
        $device1Id = $deviceCollection->first()->id->id;
        $device2Id = $deviceCollection->last()->id->id;
        thingsboard($tenantUser)->entityRelation()->saveRelation(
            new Id($device1Id, EnumEntityType::DEVICE()),
            new Id($device2Id, EnumEntityType::DEVICE()),
            'Contains',
            'COMMON'
        );

        $filters = [
            [
                'relationType' => 'ENTITY_FIELD',
                'entityTypes' => [
                    EnumEntityType::DEVICE(),
                ],
            ],
        ];

        $parameters = [
            'rootId' => $device1Id,
            'rootType' => EnumEntityType::DEVICE(),
            'direction' => 'FROM',
            'relationTypeGroup' => 'COMMON',
            'maxLevel' => 0,
            'fetchLastLevelOnly' => false,
        ];

        $findEntities = thingsboard($tenantUser)->entityRelation()->findInfoByQuery($filters, $parameters);
        $this->assertIsArray($findEntities);

        foreach ($findEntities as $entity) {
            $this->assertInstanceOf(EntityRelation::class, $entity);
        }

        $result = thingsboard($tenantUser)->entityRelation()->deleteRelation(
            new Id($device1Id, EnumEntityType::DEVICE()),
            'Contains',
            new Id($device2Id, EnumEntityType::DEVICE()),
            'COMMON'
        );

        $this->assertTrue($result);
    }

    public function testWhenSortKeyFilterTypesIsNull()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $filters = [];

        $parameters = [
            'rootId' => $this->faker->uuid,
            'rootType' => EnumEntityType::DEVICE(),
            'direction' => 'FROM',
            'relationTypeGroup' => 'COMMON',
            'maxLevel' => 0,
            'fetchLastLevelOnly' => false,
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/filters/');
        thingsboard($tenantUser)->entityRelation()->findInfoByQuery($filters, $parameters);
    }
}
