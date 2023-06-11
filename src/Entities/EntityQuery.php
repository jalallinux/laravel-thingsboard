<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumQueryEntitySortKeyFilterTypes;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $data
 * @property integer $totalPages
 * @property integer $totalElements
 * @property boolean $hasNext
 * @property array $entityFields
 * @property array $entityFilter
 * @property array $keyFilters
 * @property array $latestValues
 * @property array $pageLink
 */
class EntityQuery extends Tntity
{
    protected $fillable = [
        'data',
        'totalPages',
        'totalElements',
        'hasNext',
        'entityFields',
        'entityFilter',
        'keyFilters',
        'latestValues',
        'pageLink'
    ];

    protected $casts = [
        'data' => 'array',
        'totalPages' => 'integer',
        'totalElements' => 'integer',
        'hasNext' => 'boolean',
        'entityFields' => 'array',
        'entityFilter' => 'array',
        'keyFilters' => 'array',
        'latestValues' => 'array',
        'pageLink' => 'array'
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::ASSET();
    }

    /**
     * Allows to run complex queries over platform entities (devices, assets, customers, etc) based on the combination of main entity filter and multiple key filters.
     * Returns the paginated result of the query that contains requested entity fields and latest values of requested attributes and time-series data.
     *
     * Query Definition
     * Main entity filter is mandatory and defines generic search criteria. For example, "find all devices with profile 'Moisture Sensor'"
     * or "Find all devices related to asset 'Building A'"
     *
     * Optional key filters allow to filter results of the entity filter by complex criteria against main entity fields (name, label, type, etc),
     * attributes and telemetry. For example, "temperature > 20 or temperature< 10" or "name starts with 'T', and attribute 'model' is 'T1000', and timeseries field
     * 'batteryLevel' > 40".
     *
     * The entity fields and latest values contains list of entity fields and latest attribute/telemetry fields to fetch for each entity.
     *
     * The page link contains information about the page to fetch and the sort ordering.
     *
     * Let's review the example:
     *
     * {
     * "entityFilter": {
     * "type": "entityType",
     * "resolveMultiple": true,
     * "entityType": "DEVICE"
     * },
     * "keyFilters": [
     * {
     * "key": {
     * "type": "TIME_SERIES",
     * "key": "temperature"
     * },
     * "valueType": "NUMERIC",
     * "predicate": {
     * "operation": "GREATER",
     * "value": {
     * "defaultValue": 0,
     * "dynamicValue": {
     * "sourceType": "CURRENT_USER",
     * "sourceAttribute": "temperatureThreshold",
     * "inherit": false
     * }
     * },
     * "type": "NUMERIC"
     * }
     * }
     * ],
     * "entityFields": [
     * {
     * "type": "ENTITY_FIELD",
     * "key": "name"
     * },
     * {
     * "type": "ENTITY_FIELD",
     * "key": "label"
     * },
     * {
     * "type": "ENTITY_FIELD",
     * "key": "additionalInfo"
     * }
     * ],
     * "latestValues": [
     * {
     * "type": "ATTRIBUTE",
     * "key": "model"
     * },
     * {
     * "type": "TIME_SERIES",
     * "key": "temperature"
     * }
     * ],
     * "pageLink": {
     * "page": 0,
     * "pageSize": 10,
     * "sortOrder": {
     * "key": {
     * "key": "name",
     * "type": "ENTITY_FIELD"
     * },
     * "direction": "ASC"
     * }
     * }
     * }
     * Example mentioned above search all devices which have attribute 'active' set to 'true'.
     * Now let's review available entity filters and key filters syntax:
     *
     * Entity Filters
     * Entity Filter body depends on the 'type' parameter. Let's review available entity filter types.
     * In fact, they do correspond to available dashboard aliases.
     *
     * Single Entity
     * Allows to filter only one entity based on the id. For example, this entity filter selects certain device:
     *
     * {
     * "type": "singleEntity",
     * "singleEntity": {
     * "id": "d521edb0-2a7a-11ec-94eb-213c95f54092",
     * "entityType": "DEVICE"
     * }
     * }
     * Entity List Filter
     * Allows to filter entities of the same type using their ids.
     * For example, this entity filter selects two devices:
     *
     * {
     * "type": "entityList",
     * "entityType": "DEVICE",
     * "entityList": [
     * "e6501f30-2a7a-11ec-94eb-213c95f54092",
     * "e6657bf0-2a7a-11ec-94eb-213c95f54092"
     * ]
     * }
     * Entity Name Filter
     * Allows to filter entities of the same type using the 'starts with' expression over entity name.
     * For example, this entity filter selects all devices which name starts with 'Air Quality':
     *
     * {
     * "type": "entityName",
     * "entityType": "DEVICE",
     * "entityNameFilter": "Air Quality"
     * }
     * Entity Type Filter
     * Allows to filter entities based on their type (CUSTOMER, USER, DASHBOARD, ASSET, DEVICE, etc)For example,
     * this entity filter selects all tenant customers:
     *
     * {
     * "type": "entityType",
     * "entityType": "CUSTOMER"
     * }
     * Asset Type Filter
     * Allows to filter assets based on their type and the 'starts with' expression over their name.
     * For example, this entity filter selects all 'charging station' assets which name starts with 'Tesla':
     *
     * {
     * "type": "assetType",
     * "assetType": "charging station",
     * "assetNameFilter": "Tesla"
     * }
     * Device Type Filter
     * Allows to filter devices based on their type and the 'starts with' expression over their name.
     * For example, this entity filter selects all 'Temperature Sensor' devices which name starts with 'ABC':
     *
     * {
     * "type": "deviceType",
     * "deviceType": "Temperature Sensor",
     * "deviceNameFilter": "ABC"
     * }
     * Edge Type Filter
     * Allows to filter edge instances based on their type and the 'starts with' expression over their name.
     * For example, this entity filter selects all 'Factory' edge instances which name starts with 'Nevada':
     *
     * {
     * "type": "edgeType",
     * "edgeType": "Factory",
     * "edgeNameFilter": "Nevada"
     * }
     * Entity View Filter
     * Allows to filter entity views based on their type and the 'starts with' expression over their name.
     * For example, this entity filter selects all 'Concrete Mixer' entity views which name starts with 'CAT':
     *
     * {
     * "type": "entityViewType",
     * "entityViewType": "Concrete Mixer",
     * "entityViewNameFilter": "CAT"
     * }
     * Api Usage Filter
     * Allows to query for Api Usage based on optional customer id. If the customer id is not set, returns current tenant API usage.
     * For example, this entity filter selects the 'Api Usage' entity for customer with id 'e6501f30-2a7a-11ec-94eb-213c95f54092':
     *
     * {
     * "type": "apiUsageState",
     * "customerId": {
     * "id": "d521edb0-2a7a-11ec-94eb-213c95f54092",
     * "entityType": "CUSTOMER"
     * }
     * }
     * Relations Query Filter
     * Allows to filter entities that are related to the provided root entity. Possible direction values are 'TO' and 'FROM'.
     * The 'maxLevel' defines how many relation levels should the query search 'recursively'. Assuming the 'maxLevel' is > 1, the 'fetchLastLevelOnly'
     * defines either to return all related entities or only entities that are on the last level of relations. The 'filter'
     * object allows you to define the relation type and set of acceptable entity types to search for. The relation query calculates all related entities,
     * even if they are filtered using different relation types, and then extracts only those who match the 'filters'.
     *
     * For example, this entity filter selects all devices and assets which are related to the asset with id 'e51de0c0-2a7a-11ec-94eb-213c95f54092':
     *
     * {
     * "type": "relationsQuery",
     * "rootEntity": {
     * "entityType": "ASSET",
     * "id": "e51de0c0-2a7a-11ec-94eb-213c95f54092"
     * },
     * "direction": "FROM",
     * "maxLevel": 1,
     * "fetchLastLevelOnly": false,
     * "filters": [
     * {
     * "relationType": "Contains",
     * "entityTypes": [
     * "DEVICE",
     * "ASSET"
     * ]
     * }
     * ]
     * }
     * Asset Search Query
     * Allows to filter assets that are related to the provided root entity.
     * Filters related assets based on the relation type and set of asset types.
     * Possible direction values are 'TO' and 'FROM'. The 'maxLevel' defines how many
     * relation levels should the query search 'recursively'. Assuming the 'maxLevel' is > 1, the 'fetchLastLevelOnly'
     * defines either to return all related entities or only entities that are on the last level of relations.
     * The 'relationType' defines the type of the relation to search for.
     * The 'assetTypes' defines the type of the asset to search for.
     * The relation query calculates all related entities, even if they are filtered using different relation types,
     * and then extracts only assets that match 'relationType' and 'assetTypes' conditions.
     *
     * For example, this entity filter selects 'charging station' assets which are related to the asset with id 'e51de0c0-2a7a-11ec-94eb-213c95f54092' using 'Contains' relation:
     *
     * {
     * "type": "assetSearchQuery",
     * "rootEntity": {
     * "entityType": "ASSET",
     * "id": "e51de0c0-2a7a-11ec-94eb-213c95f54092"
     * },
     * "direction": "FROM",
     * "maxLevel": 1,
     * "fetchLastLevelOnly": false,
     * "relationType": "Contains",
     * "assetTypes": [
     * "charging station"
     * ]
     * }
     * Device Search Query
     * Allows to filter devices that are related to the provided root entity.
     * Filters related devices based on the relation type and set of device types.
     * Possible direction values are 'TO' and 'FROM'. The 'maxLevel' defines how many relation levels should the query search 'recursively'.
     * Assuming the 'maxLevel' is > 1, the 'fetchLastLevelOnly' defines either to return all related entities or only
     * entities that are on the last level of relations. The 'relationType' defines the type of the relation to search for.
     * The 'deviceTypes' defines the type of the device to search for. The relation query calculates all related entities,
     * even if they are filtered using different relation types, and then extracts only devices that match 'relationType' and 'deviceTypes' conditions.
     *
     * For example, this entity filter selects 'Charging port' and 'Air Quality Sensor' devices which are related to the asset with id 'e52b0020-2a7a-11ec-94eb-213c95f54092' using 'Contains' relation:
     *
     * {
     * "type": "deviceSearchQuery",
     * "rootEntity": {
     * "entityType": "ASSET",
     * "id": "e52b0020-2a7a-11ec-94eb-213c95f54092"
     * },
     * "direction": "FROM",
     * "maxLevel": 2,
     * "fetchLastLevelOnly": true,
     * "relationType": "Contains",
     * "deviceTypes": [
     * "Air Quality Sensor",
     * "Charging port"
     * ]
     * }
     * Entity View Query
     * Allows to filter entity views that are related to the provided root entity. Filters related entity views based on the relation type and set of entity view types.
     * Possible direction values are 'TO' and 'FROM'. The 'maxLevel' defines how many relation levels should the query search 'recursively'.
     * Assuming the 'maxLevel' is > 1, the 'fetchLastLevelOnly' defines either to return all related entities or only entities that are on the last level of relations.
     * The 'relationType' defines the type of the relation to search for. The 'entityViewTypes' defines the type of the entity view to search for.
     * The relation query calculates all related entities, even if they are filtered using different relation types, and then extracts only devices that match 'relationType'
     * and 'deviceTypes' conditions.
     *
     * For example, this entity filter selects 'Concrete mixer' entity views which are related to the asset with id 'e52b0020-2a7a-11ec-94eb-213c95f54092' using 'Contains' relation:
     *
     * {
     * "type": "entityViewSearchQuery",
     * "rootEntity": {
     * "entityType": "ASSET",
     * "id": "e52b0020-2a7a-11ec-94eb-213c95f54092"
     * },
     * "direction": "FROM",
     * "maxLevel": 1,
     * "fetchLastLevelOnly": false,
     * "relationType": "Contains",
     * "entityViewTypes": [
     * "Concrete mixer"
     * ]
     * }
     * Edge Search Query
     * Allows to filter edge instances that are related to the provided root entity.
     * Filters related edge instances based on the relation type and set of edge types.
     * Possible direction values are 'TO' and 'FROM'. The 'maxLevel' defines how many relation
     * levels should the query search 'recursively'. Assuming the 'maxLevel' is > 1, the 'fetchLastLevelOnly'
     * defines either to return all related entities or only entities that are on the last level of relations.
     * The 'relationType' defines the type of the relation to search for. The 'deviceTypes' defines the type of
     * the device to search for. The relation query calculates all related entities, even if they are filtered using
     * different relation types, and then extracts only devices that match 'relationType' and 'deviceTypes' conditions.
     *
     * For example, this entity filter selects 'Factory' edge instances which are related to the asset with id 'e52b0020-2a7a-11ec-94eb-213c95f54092' using 'Contains' relation:
     *
     * {
     * "type": "deviceSearchQuery",
     * "rootEntity": {
     * "entityType": "ASSET",
     * "id": "e52b0020-2a7a-11ec-94eb-213c95f54092"
     * },
     * "direction": "FROM",
     * "maxLevel": 2,
     * "fetchLastLevelOnly": true,
     * "relationType": "Contains",
     * "edgeTypes": [
     * "Factory"
     * ]
     * }
     * Key Filters
     * Key Filter allows you to define complex logical expressions over entity field, attribute or latest time-series value.
     * The filter is defined using 'key', 'valueType' and 'predicate' objects. Single Entity Query may have zero, one or multiple predicates.
     * If multiple filters are defined, they are evaluated using logical 'AND'. The example below checks that temperature of the entity is above 20 degrees:
     *
     * {
     * "key": {
     * "type": "TIME_SERIES",
     * "key": "temperature"
     * },
     * "valueType": "NUMERIC",
     * "predicate": {
     * "operation": "GREATER",
     * "value": {
     * "defaultValue": 20,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * }
     * }
     * Now let's review 'key', 'valueType' and 'predicate' objects in detail.
     *
     * Filter Key
     * Filter Key defines either entity field, attribute or telemetry.
     * It is a JSON object that consists the key name and type.
     * The following filter key types are supported:
     *
     * 'CLIENT_ATTRIBUTE' - used for client attributes;
     * 'SHARED_ATTRIBUTE' - used for shared attributes;
     * 'SERVER_ATTRIBUTE' - used for server attributes;
     * 'ATTRIBUTE' - used for any of the above;
     * 'TIME_SERIES' - used for time-series values;
     * 'ENTITY_FIELD' - used for accessing entity fields like 'name', 'label', etc. The list of available fields depends on the entity type;
     * 'ALARM_FIELD' - similar to entity field, but is used in alarm queries only;
     * Let's review the example:
     *
     * {
     * "type": "TIME_SERIES",
     * "key": "temperature"
     * }
     * Value Type and Operations
     * Provides a hint about the data type of the entity field that is defined in the filter key. The value type impacts the list of
     * possible operations that you may use in the corresponding predicate. For example, you may use 'STARTS_WITH' or 'END_WITH',
     * but you can't use 'GREATER_OR_EQUAL' for string values.The following filter value types and corresponding predicate operations are supported:
     *
     * 'STRING' - used to filter any 'String' or 'JSON' values. Operations: EQUAL, NOT_EQUAL, STARTS_WITH, ENDS_WITH, CONTAINS, NOT_CONTAINS;
     * 'NUMERIC' - used for 'Long' and 'Double' values. Operations: EQUAL, NOT_EQUAL, GREATER, LESS, GREATER_OR_EQUAL, LESS_OR_EQUAL;
     * 'BOOLEAN' - used for boolean values. Operations: EQUAL, NOT_EQUAL;
     * 'DATE_TIME' - similar to numeric, transforms value to milliseconds since epoch. Operations: EQUAL, NOT_EQUAL, GREATER, LESS, GREATER_OR_EQUAL, LESS_OR_EQUAL;
     * Filter Predicate
     * Filter Predicate defines the logical expression to evaluate.
     * The list of available operations depends on the filter value type, see above. Platform supports 4 predicate types: 'STRING', 'NUMERIC', 'BOOLEAN' and 'COMPLEX'.
     * The last one allows to combine multiple operations over one filter key.
     *
     * Simple predicate example to check 'value < 100':
     *
     * {
     * "operation": "LESS",
     * "value": {
     * "defaultValue": 100,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * }
     * Complex predicate example, to check 'value < 10 or value > 20':
     *
     * {
     * "type": "COMPLEX",
     * "operation": "OR",
     * "predicates": [
     * {
     * "operation": "LESS",
     * "value": {
     * "defaultValue": 10,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * },
     * {
     * "operation": "GREATER",
     * "value": {
     * "defaultValue": 20,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * }
     * ]
     * }
     * More complex predicate example, to check 'value < 10 or (value > 50 && value < 60)':
     *
     * {
     * "type": "COMPLEX",
     * "operation": "OR",
     * "predicates": [
     * {
     * "operation": "LESS",
     * "value": {
     * "defaultValue": 10,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * },
     * {
     * "type": "COMPLEX",
     * "operation": "AND",
     * "predicates": [
     * {
     * "operation": "GREATER",
     * "value": {
     * "defaultValue": 50,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * },
     * {
     * "operation": "LESS",
     * "value": {
     * "defaultValue": 60,
     * "dynamicValue": null
     * },
     * "type": "NUMERIC"
     * }
     * ]
     * }
     * ]
     * }
     * You may also want to replace hardcoded values (for example, temperature > 20) with the more dynamic expression
     * (for example, temperature > 'value of the tenant attribute with key 'temperatureThreshold').
     * It is possible to use 'dynamicValue' to define attribute of the tenant, customer or user that is performing the API call.
     * See example below:
     *
     * {
     * "operation": "GREATER",
     * "value": {
     * "defaultValue": 0,
     * "dynamicValue": {
     * "sourceType": "CURRENT_USER",
     * "sourceAttribute": "temperatureThreshold"
     * }
     * },
     * "type": "NUMERIC"
     * }
     * Note that you may use 'CURRENT_USER', 'CURRENT_CUSTOMER' and 'CURRENT_TENANT' as a 'sourceType'. The 'defaultValue'
     * is used when the attribute with such a name is not defined for the chosen source.
     * @param PaginationArguments $paginationArguments
     * @param array $entityFields
     * @param array $entityFilter
     * @param array $keyFilters
     * @param EnumQueryEntitySortKeyFilterTypes|null $sortOrderKeyType
     * @param bool $dynamic
     * @return array
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function findEntityDataByQuery(PaginationArguments $paginationArguments, array $entityFields, array $entityFilter, array $keyFilters, EnumQueryEntitySortKeyFilterTypes $sortOrderKeyType = null, bool $dynamic = true): array
    {
        $payload = [];
        Thingsboard::validation(empty($entityFields), 'required', ['attribute' => 'entityFields']);
        Thingsboard::validation(empty($entityFilter), 'required', ['attribute' => 'entityFilter']);
        Thingsboard::validation(empty($keyFilters), 'required', ['attribute' => 'keyFilters']);
        Thingsboard::validation(! is_null(@$paginationArguments->sortProperty) && is_null($sortOrderKeyType), 'required', ['attribute' => 'sortOrderKeyType']);

        $payload['entityFields'] = $entityFields;
        $payload['entityFilter'] = $entityFilter;
        $payload['keyFilters'] = $keyFilters;

        $payload['pageLink'] = array_merge($paginationArguments->queryParams(),[
            'sortOrder' => [
                'direction' => @$paginationArguments->sortOrder,
                'key' => [
                    'key' => $paginationArguments->sortProperty,
                    'type' => $sortOrderKeyType->value
                ]
            ]
        ]);

        return $this->api()->post('entitiesQuery/find', $payload)->json();
    }
}
