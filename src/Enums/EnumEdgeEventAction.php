<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ADDED()
 * @method static self ALARM_ACK()
 * @method static self ALARM_ASSIGNED()
 * @method static self ALARM_CLEAR()
 * @method static self ALARM_UNASSIGNED()
 * @method static self ASSIGNED_TO_CUSTOMER()
 * @method static self ASSIGNED_TO_EDGE()
 * @method static self ATTRIBUTES_DELETED()
 * @method static self ATTRIBUTES_UPDATED()
 * @method static self CREDENTIALS_REQUEST()
 * @method static self CREDENTIALS_UPDATED()
 * @method static self DELETED()
 * @method static self ENTITY_MERGE_REQUEST()
 * @method static self POST_ATTRIBUTES()
 * @method static self RELATION_ADD_OR_UPDATE()
 * @method static self RELATION_DELETED()
 * @method static self RPC_CALL()
 * @method static self TIMESERIES_UPDATED()
 * @method static self UNASSIGNED_FROM_CUSTOMER()
 * @method static self UNASSIGNED_FROM_EDGE()
 * @method static self UPDATED()
 */
class EnumEdgeEventAction extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'ADDED' => 'ADDED',
            'ALARM_ACK' => 'ALARM_ACK',
            'ALARM_ASSIGNED' => 'ALARM_ASSIGNED',
            'ALARM_CLEAR' => 'ALARM_CLEAR',
            'ALARM_UNASSIGNED' => 'ALARM_UNASSIGNED',
            'ASSIGNED_TO_CUSTOMER' => 'ASSIGNED_TO_CUSTOMER',
            'ASSIGNED_TO_EDGE' => 'ASSIGNED_TO_EDGE',
            'ATTRIBUTES_DELETED' => 'ATTRIBUTES_DELETED',
            'ATTRIBUTES_UPDATED' => 'ATTRIBUTES_UPDATED',
            'CREDENTIALS_REQUEST' => 'CREDENTIALS_REQUEST',
            'CREDENTIALS_UPDATED' => 'CREDENTIALS_UPDATED',
            'DELETED' => 'DELETED',
            'ENTITY_MERGE_REQUEST' => 'ENTITY_MERGE_REQUEST',
            'POST_ATTRIBUTES' => 'POST_ATTRIBUTES',
            'RELATION_ADD_OR_UPDATE' => 'RELATION_ADD_OR_UPDATE',
            'RELATION_DELETED' => 'RELATION_DELETED',
            'RPC_CALL' => 'RPC_CALL',
            'TIMESERIES_UPDATED' => 'TIMESERIES_UPDATED',
            'UNASSIGNED_FROM_CUSTOMER' => 'UNASSIGNED_FROM_CUSTOMER',
            'UNASSIGNED_FROM_EDGE' => 'UNASSIGNED_FROM_EDGE',
            'UPDATED' => 'UPDATED',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ADDED' => 'Added',
            'ALARM_ACK' => 'Alarm Ack',
            'ALARM_ASSIGNED' => 'Alarm Assigned',
            'ALARM_CLEAR' => 'Alarm Clear',
            'ALARM_UNASSIGNED' => 'Alarm UnAssign',
            'ASSIGNED_TO_CUSTOMER' => 'Assigned To Customer',
            'ASSIGNED_TO_EDGE' => 'Assigned To Edge',
            'ATTRIBUTES_DELETED' => 'Attribute Deleted',
            'ATTRIBUTES_UPDATED' => 'Attribute Updated',
            'CREDENTIALS_REQUEST' => 'Credentials Request',
            'CREDENTIALS_UPDATED' => 'Credentials Updated',
            'DELETED' => 'Deleted',
            'ENTITY_MERGE_REQUEST' => 'Entity Merge request',
            'POST_ATTRIBUTES' => 'Post Attributes',
            'RELATION_ADD_OR_UPDATE' => 'Relation Add Or Update',
            'RELATION_DELETED' => 'Relation Deleted',
            'RPC_CALL' => 'RPC Call',
            'TIMESERIES_UPDATED' => 'Timeseries Updated',
            'UNASSIGNED_FROM_CUSTOMER' => 'UnAssigned From Customer',
            'UNASSIGNED_FROM_EDGE' => 'UnAssigned From Edge',
            'UPDATED' => 'Updated',
        ];
    }
}
