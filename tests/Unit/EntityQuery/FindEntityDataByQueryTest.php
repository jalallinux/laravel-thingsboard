<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\EntityQuery;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumQueryEntitySortKeyFilterTypes;
use JalalLinuX\Thingsboard\Enums\EnumTenantProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumTenantSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class FindEntityDataByQueryTest extends TestCase
{
    public function testFetchSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $randEnum = $this->faker->randomElement([EnumTenantProfileSortProperty::class, EnumTenantSortProperty::class]);
        $sortProperty = $this->faker->randomElement($randEnum::cases());
        $entityFields = [
            [
                "type" => "ENTITY_FIELD",
                "key" => "name"
            ]
        ];
        $entityFilter = [
            "type" => "apiUsageState"
        ];
        $keyFilters = [
            [
                "key" => [
                    "type" => "TIME_SERIES",
                    "key" => "temperature"
                ],
                "predicate" => [
                    "operation" => "GREATER",
                    "value" => [
                        "defaultValue" => 20,
                        "dynamicValue" => null
                    ],
                    "type" => "NUMERIC"
                ],
                "valueType" => "NUMERIC"
            ]
        ];
        $findEntities = thingsboard($tenantUser)->entityQuery()->findEntityDataByQuery(PaginationArguments::make(sortProperty: $sortProperty), $entityFields, $entityFilter, $keyFilters, EnumQueryEntitySortKeyFilterTypes::ENTITY_FIELD());

        $this->assertIsArray($findEntities);
        $this->assertArrayHasKey('data', $findEntities);
    }

    public function testWhenSortKeyFilterTypesIsNull()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $randEnum = $this->faker->randomElement([EnumTenantProfileSortProperty::class, EnumTenantSortProperty::class]);
        $sortProperty = $this->faker->randomElement($randEnum::cases());
        $entityFields = [
            [
                "type" => "ENTITY_FIELD",
                "key" => "name"
            ]
        ];
        $entityFilter = [
            "type" => "apiUsageState"
        ];
        $keyFilters = [
            [
                "key" => [
                    "type" => "TIME_SERIES",
                    "key" => "temperature"
                ],
                "predicate" => [
                    "operation" => "GREATER",
                    "value" => [
                        "defaultValue" => 20,
                        "dynamicValue" => null
                    ],
                    "type" => "NUMERIC"
                ],
                "valueType" => "NUMERIC"
            ]
        ];
        $this->expectExceptionCode(500);
        $this->expectExceptionMessageMatches('/sortOrderKeyType/');
        thingsboard($tenantUser)->entityQuery()->findEntityDataByQuery(PaginationArguments::make(sortProperty: $sortProperty), $entityFields, $entityFilter, $keyFilters);
    }
}
