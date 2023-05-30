<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property int $alarms;
 * @property int $assets;
 * @property int $customers;
 * @property int $dashboards;
 * @property int $devices;
 * @property int $emails;
 * @property int $jsExecutions;
 * @property int $maxAlarms;
 * @property int $maxAssets;
 * @property int $maxCustomers;
 * @property int $maxDashboards;
 * @property int $maxDevices;
 * @property int $maxEmails;
 * @property int $maxJsExecutions;
 * @property int $maxSms;
 * @property int $maxTransportMessages;
 * @property int $maxUsers;
 * @property int $sms;
 * @property int $transportMessages;
 * @property int $users;
 */
class Usage extends Tntity
{
    protected $fillable = [
        'alarms',
        'assets',
        'customers',
        'dashboards',
        'devices',
        'emails',
        'jsExecutions',
        'maxAlarms',
        'maxAssets',
        'maxCustomers',
        'maxDashboards',
        'maxDevices',
        'maxEmails',
        'maxJsExecutions',
        'maxSms',
        'maxTransportMessages',
        'maxUsers',
        'sms',
        'transportMessages',
        'users',
    ];

    protected $casts = [
        'alarms' => 'int',
        'assets' => 'int',
        'customers' => 'int',
        'dashboards' => 'int',
        'devices' => 'int',
        'emails' => 'int',
        'jsExecutions' => 'int',
        'maxAlarms' => 'int',
        'maxAssets' => 'int',
        'maxCustomers' => 'int',
        'maxDashboards' => 'int',
        'maxDevices' => 'int',
        'maxEmails' => 'int',
        'maxJsExecutions' => 'int',
        'maxSms' => 'int',
        'maxTransportMessages' => 'int',
        'maxUsers' => 'int',
        'sms' => 'int',
        'transportMessages' => 'int',
        'users' => 'int',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Get Tenant Usage Info
     * @return self
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function getTenantUsageInfo(): static
    {
        $usage = $this->api()->get('usage')->json();

        return tap($this, fn () => $this->fill($usage));
    }
}
