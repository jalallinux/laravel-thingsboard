<?php

namespace JalalLinuX\Thingsboard\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ALARM()
 * @method static self ASSET()
 * @method static self ASSET_PROFILE()
 * @method static self CUSTOMER()
 * @method static self DASHBOARD()
 * @method static self DEVICE()
 * @method static self DEVICE_PROFILE()
 * @method static self EDGE()
 * @method static self ENTITY_VIEW()
 * @method static self OTA_PACKAGE()
 * @method static self RPC()
 * @method static self RULE_CHAIN()
 * @method static self RULE_NODE()
 * @method static self TB_RESOURCE()
 * @method static self TENANT()
 * @method static self TENANT_PROFILE()
 * @method static self USER()
 * @method static self WIDGET_TYPE()
 * @method static self WIDGETS_BUNDLE()
 */
class ThingsboardEntityType extends Enum
{
    protected static function values(): array
    {
        return [
            'ALARM' => 'ALARM',
            'ASSET' => 'ASSET',
            'ASSET_PROFILE' => 'ASSET_PROFILE',
            'CUSTOMER' => 'CUSTOMER',
            'DASHBOARD' => 'DASHBOARD',
            'DEVICE' => 'DEVICE',
            'DEVICE_PROFILE' => 'DEVICE_PROFILE',
            'EDGE' => 'EDGE',
            'ENTITY_VIEW' => 'ENTITY_VIEW',
            'OTA_PACKAGE' => 'OTA_PACKAGE',
            'RPC' => 'RPC',
            'RULE_CHAIN' => 'RULE_CHAIN',
            'RULE_NODE' => 'RULE_NODE',
            'TB_RESOURCE' => 'TB_RESOURCE',
            'TENANT' => 'TENANT',
            'TENANT_PROFILE' => 'TENANT_PROFILE',
            'USER' => 'USER',
            'WIDGET_TYPE' => 'WIDGET_TYPE',
            'WIDGETS_BUNDLE' => 'WIDGETS_BUNDLE',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ALARM' => 'Alarm',
            'ASSET' => 'Asset',
            'ASSET_PROFILE' => 'Asset Profile',
            'CUSTOMER' => 'Customer',
            'DASHBOARD' => 'Dashboard',
            'DEVICE' => 'Device',
            'DEVICE_PROFILE' => 'Device Profile',
            'EDGE' => 'Edge',
            'ENTITY_VIEW' => 'Entity View',
            'OTA_PACKAGE' => 'OTA Pacakge',
            'RPC' => 'RPC',
            'RULE_CHAIN' => 'Rule Chain',
            'RULE_NODE' => 'Rule Node',
            'TB_RESOURCE' => 'TB Resource',
            'TENANT' => 'Tenant',
            'TENANT_PROFILE' => 'Tenant Profile',
            'USER' => 'User',
            'WIDGET_TYPE' => 'Widget Type',
            'WIDGETS_BUNDLE' => 'Widgets Bundle',
        ];
    }
}
