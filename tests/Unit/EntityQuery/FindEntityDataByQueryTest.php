<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\EntityQuery;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumDeviceSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumQueryEntitySortKeyFilterTypes;
use JalalLinuX\Thingsboard\Enums\EnumSortOrder;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class FindEntityDataByQueryTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumDeviceSortProperty::cases());
        $entityFields = [
            [
                'type' => 'ENTITY_FIELD',
                'key' => 'name',
            ],
        ];
        $entityFilter = [
            'type' => 'apiUsageState',
            'DeviceId' => [
                'id' => thingsboard($tenantUser)->device()->getTenantDeviceInfos(PaginationArguments::make())->first()->id->id,
                'entityType' => 'DEVICE',
            ],
        ];
        $keyFilters = [
            [
                'key' => [
                    'type' => 'TIME_SERIES',
                    'key' => 'temperature',
                ],
                'predicate' => [
                    'operation' => 'GREATER',
                    'value' => [
                        'defaultValue' => 20,
                        'dynamicValue' => null,
                    ],
                    'type' => 'NUMERIC',
                ],
                'valueType' => 'NUMERIC',
            ],
        ];
        $findEntities = thingsboard($tenantUser)->entityQuery()->findEntityDataByQuery(PaginationArguments::make(0, 1, $sortProperty, EnumSortOrder::DESC()), $entityFilter, $entityFields, $keyFilters, EnumQueryEntitySortKeyFilterTypes::ENTITY_FIELD());

        $this->assertIsArray($findEntities);
        $this->assertArrayHasKey('data', $findEntities);
    }

    public function testWhenSortKeyFilterTypesIsNull()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $sortProperty = $this->faker->randomElement(EnumQueryEntitySortKeyFilterTypes::cases());
        $entityFields = [
            [
                'type' => 'ENTITY_FIELD',
                'key' => 'name',
            ],
        ];
        $entityFilter = [
            'type' => 'apiUsageState',
        ];
        $keyFilters = [
            [
                'key' => [
                    'type' => 'TIME_SERIES',
                    'key' => 'temperature',
                ],
                'predicate' => [
                    'operation' => 'GREATER',
                    'value' => [
                        'defaultValue' => 20,
                        'dynamicValue' => null,
                    ],
                    'type' => 'NUMERIC',
                ],
                'valueType' => 'NUMERIC',
            ],
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/sortOrderKeyType/');
        thingsboard($tenantUser)->entityQuery()->findEntityDataByQuery(PaginationArguments::make(), $entityFilter, $entityFields, $keyFilters);
    }
}
