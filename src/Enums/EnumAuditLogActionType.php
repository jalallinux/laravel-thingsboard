<?php

namespace JalalLinuX\Thingsboard\Enums;

/**
 * @method static self ACTIVATED()
 * @method static self ADDED()
 * @method static self ADDED_COMMENT()
 * @method static self ALARM_ACK()
 * @method static self ALARM_ASSIGNED()
 * @method static self ALARM_CLEAR()
 * @method static self ALARM_DELETE()
 * @method static self ALARM_UNASSIGNED()
 * @method static self ASSIGNED_FROM_TENANT()
 * @method static self ASSIGNED_TO_CUSTOMER()
 * @method static self ASSIGNED_TO_EDGE()
 * @method static self ASSIGNED_TO_TENANT()
 * @method static self ATTRIBUTES_DELETED()
 * @method static self ATTRIBUTES_READ()
 * @method static self ATTRIBUTES_UPDATED()
 * @method static self CREDENTIALS_READ()
 * @method static self CREDENTIALS_UPDATED()
 * @method static self DELETED()
 * @method static self DELETED_COMMENT()
 * @method static self LOCKOUT()
 * @method static self LOGIN()
 * @method static self LOGOUT()
 * @method static self PROVISION_FAILURE()
 * @method static self PROVISION_SUCCESS()
 * @method static self RELATIONS_DELETED()
 * @method static self RELATION_ADD_OR_UPDATE()
 * @method static self RELATION_DELETED()
 * @method static self RPC_CALL()
 * @method static self SUSPENDED()
 * @method static self TIMESERIES_DELETED()
 * @method static self TIMESERIES_UPDATED()
 * @method static self UNASSIGNED_FROM_CUSTOMER()
 * @method static self UNASSIGNED_FROM_EDGE()
 * @method static self UPDATED()
 * @method static self UPDATED_COMMENT()
 */
class EnumAuditLogActionType extends BaseEnum
{
    protected static function values(): array
    {
        return [
            'ACTIVATED' => 'ACTIVATED',
            'ADDED' => 'ADDED',
            'ADDED_COMMENT' => 'ADDED_COMMENT',
            'ALARM_ACK' => 'ALARM_ACK',
            'ALARM_ASSIGNED' => 'ALARM_ASSIGNED',
            'ALARM_CLEAR' => 'ALARM_CLEAR',
            'ALARM_DELETE' => 'ALARM_DELETE',
            'ALARM_UNASSIGNED' => 'ALARM_UNASSIGNED',
            'ASSIGNED_FROM_TENANT' => 'ASSIGNED_FROM_TENANT',
            'ASSIGNED_TO_CUSTOMER' => 'ASSIGNED_TO_CUSTOMER',
            'ASSIGNED_TO_EDGE' => 'ASSIGNED_TO_EDGE',
            'ASSIGNED_TO_TENANT' => 'ASSIGNED_TO_TENANT',
            'ATTRIBUTES_DELETED' => 'ATTRIBUTES_DELETED',
            'ATTRIBUTES_READ' => 'ATTRIBUTES_READ',
            'ATTRIBUTES_UPDATED' => 'ATTRIBUTES_UPDATED',
            'CREDENTIALS_READ' => 'CREDENTIALS_READ',
            'CREDENTIALS_UPDATED' => 'CREDENTIALS_UPDATED',
            'DELETED' => 'DELETED',
            'DELETED_COMMENT' => 'DELETED_COMMENT',
            'LOCKOUT' => 'LOCKOUT',
            'LOGIN' => 'LOGIN',
            'LOGOUT' => 'LOGOUT',
            'PROVISION_FAILURE' => 'PROVISION_FAILURE',
            'PROVISION_SUCCESS' => 'PROVISION_SUCCESS',
            'RELATIONS_DELETED' => 'RELATIONS_DELETED',
            'RELATION_ADD_OR_UPDATE' => 'RELATION_ADD_OR_UPDATE',
            'RELATION_DELETED' => 'RELATION_DELETED',
            'RPC_CALL' => 'RPC_CALL',
            'SUSPENDED' => 'SUSPENDED',
            'TIMESERIES_DELETED' => 'TIMESERIES_DELETED',
            'TIMESERIES_UPDATED' => 'TIMESERIES_UPDATED',
            'UNASSIGNED_FROM_CUSTOMER' => 'UNASSIGNED_FROM_CUSTOMER',
            'UNASSIGNED_FROM_EDGE' => 'UNASSIGNED_FROM_EDGE',
            'UPDATED' => 'UPDATED',
            'UPDATED_COMMENT' => 'UPDATED_COMMENT',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACTIVATED' => 'Activated',
            'ADDED' => 'Added',
            'ADDED_COMMENT' => 'Added comment',
            'ALARM_ACK' => 'Aram ack',
            'ALARM_ASSIGNED' => 'Alarm assign',
            'ALARM_CLEAR' => 'Alarm clear',
            'ALARM_DELETE' => 'Alarm delete',
            'ALARM_UNASSIGNED' => 'Alarm unassigned',
            'ASSIGNED_FROM_TENANT' => 'Assigned from tenant',
            'ASSIGNED_TO_CUSTOMER' => 'Assigned to customer',
            'ASSIGNED_TO_EDGE' => 'Assigned to edge',
            'ASSIGNED_TO_TENANT' => 'Assigned to tenant',
            'ATTRIBUTES_DELETED' => 'Attributes delete',
            'ATTRIBUTES_READ' => 'Attributes read',
            'ATTRIBUTES_UPDATED' => 'Attributes update',
            'CREDENTIALS_READ' => 'Credentials read',
            'CREDENTIALS_UPDATED' => 'Credentials update',
            'DELETED' => 'Delete',
            'DELETED_COMMENT' => 'Delete comment',
            'LOCKOUT' => 'Lockout',
            'LOGIN' => 'Login',
            'LOGOUT' => 'Logout',
            'PROVISION_FAILURE' => 'Provision failure',
            'PROVISION_SUCCESS' => 'Provision success',
            'RELATIONS_DELETED' => 'Relations deleted',
            'RELATION_ADD_OR_UPDATE' => 'Relation add or update',
            'RELATION_DELETED' => 'Relation deleted',
            'RPC_CALL' => 'Rpc call',
            'SUSPENDED' => 'Suspended',
            'TIMESERIES_DELETED' => 'Timeseries deleted',
            'TIMESERIES_UPDATED' => 'Timeseries updated',
            'UNASSIGNED_FROM_CUSTOMER' => 'Unassigned from customer',
            'UNASSIGNED_FROM_EDGE' => 'Unassigned from edge',
            'UPDATED' => 'Update',
            'UPDATED_COMMENT' => 'Update comment',
        ];
    }
}
