<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ADMIN_SETTINGS()
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
 * @method static self QUEUE()
 * @method static self RELATION()
 * @method static self RULE_CHAIN()
 * @method static self RULE_CHAIN_METADATA()
 * @method static self TENANT()
 * @method static self USER()
 * @method static self WIDGETS_BUNDLE()
 * @method static self WIDGET_TYPE()
 */
class EnumEdgeEventType extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'ADMIN_SETTINGS' => 'ADMIN_SETTINGS',
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
            'QUEUE' => 'QUEUE',
            'RELATION' => 'RELATION',
            'RULE_CHAIN' => 'RULE_CHAIN',
            'RULE_CHAIN_METADATA' => 'RULE_CHAIN_METADATA',
            'TENANT' => 'TENANT',
            'USER' => 'USER',
            'WIDGETS_BUNDLE' => 'WIDGETS_BUNDLE',
            'WIDGET_TYPE' => 'WIDGET_TYPE',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ADMIN_SETTINGS' => 'Admin Settings',
            'ALARM' => 'Alarm',
            'ASSET' => 'Asset',
            'ASSET_PROFILE' => 'Asset Profile',
            'CUSTOMER' => 'Customer',
            'DASHBOARD' => 'Dashboard',
            'DEVICE' => 'Device',
            'DEVICE_PROFILE' => 'Device Profile',
            'EDGE' => 'Edge',
            'ENTITY_VIEW' => 'Entity View',
            'OTA_PACKAGE' => 'OTA Package',
            'QUEUE' => 'Queue',
            'RELATION' => 'Relation',
            'RULE_CHAIN' => 'RuleChain',
            'RULE_CHAIN_METADATA' => 'RuleChain Metadata',
            'TENANT' => 'tenant',
            'USER' => 'User',
            'WIDGETS_BUNDLE' => 'Widget Bundle',
            'WIDGET_TYPE' => 'Widget Type',
        ];
    }
}
