<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self API_USAGE_STATE()
 * @method static self ASSET_SEARCH_QUERY()
 * @method static self ASSET_TYPE()
 * @method static self DEVICE_SEARCH_QUERY()
 * @method static self DEVICE_TYPE()
 * @method static self EDGE_SEARCH_QUERY()
 * @method static self EDGE_TYPE()
 * @method static self ENTITY_LIST()
 * @method static self ENTITY_NAME()
 * @method static self ENTITY_TYPE()
 * @method static self ENTITY_VIEW_SEARCH_QUERY()
 * @method static self ENTITY_VIEW_TYPE()
 * @method static self RELATIONS_QUERY()
 * @method static self SINGLE_ENTITY()
 */
class EnumQueryEntityFilterTypes extends Enum
{
    protected static function values(): array
    {
        return [
            'API_USAGE_STATE' => 'apiUsageState',
            'ASSET_SEARCH_QUERY' => 'assetSearchQuery',
            'ASSET_TYPE' => 'assetType',
            'DEVICE_SEARCH_QUERY' => 'deviceSearchQuery',
            'DEVICE_TYPE' => 'deviceType',
            'EDGE_SEARCH_QUERY' => 'edgeSearchQuery',
            'EDGE_TYPE' => 'edgeType',
            'ENTITY_LIST' => 'entityList',
            'ENTITY_NAME' => 'entityName',
            'ENTITY_TYPE' => 'entityType',
            'ENTITY_VIEW_SEARCH_QUERY' => 'entityViewSearchQuery',
            'ENTITY_VIEW_TYPE' => 'entityViewType',
            'RELATIONS_QUERY' => 'relationsQuery',
            'SINGLE_ENTITY' => 'singleEntity',
        ];
    }

    protected static function labels(): array
    {
        return [
            'API_USAGE_STATE' => 'Api Usage State',
            'ASSET_SEARCH_QUERY' => 'Asset Search Query',
            'ASSET_TYPE' => 'Asset Type',
            'DEVICE_SEARCH_QUERY' => 'Device Search Query',
            'DEVICE_TYPE' => 'Device Type',
            'EDGE_SEARCH_QUERY' => 'Edge Search Query',
            'EDGE_TYPE' => 'Edge Type',
            'ENTITY_LIST' => 'Entity List',
            'ENTITY_NAME' => 'Entity Name',
            'ENTITY_TYPE' => 'Entity Type',
            'ENTITY_VIEW_SEARCH_QUERY' => 'Entity View Search Query',
            'ENTITY_VIEW_TYPE' => 'Entity View Type',
            'RELATIONS_QUERY' => 'Relations Query',
            'SINGLE_ENTITY' => 'Single Entity',
        ];
    }
}
