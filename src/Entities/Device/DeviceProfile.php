<?php

namespace JalalLinuX\Tntity\Entities\Device;

use JalalLinuX\Tntity\Entities\Tntity;

class DeviceProfile extends Tntity
{
    protected $fillable = [];

    protected $attributes = [
        'id',
        'createdTime',
        'name',
        'type',
        'image',
        'transportType',
        'provisionType',
        'profileData',
        'description',
        'searchText',
        'isDefault',
        'tenantId',
        'firmwareId',
        'softwareId',
        'defaultRuleChainId',
        'defaultDashboardId',
        'defaultQueueName',
        'provisionDeviceKey',
        'externalId',
    ];
}
