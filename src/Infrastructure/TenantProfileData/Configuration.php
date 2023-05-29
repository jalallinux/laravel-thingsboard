<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData;

class Configuration
{
    private int $alarmsTtlDays = 0;

    private ?RateLimits $cassandraQueryTenantRateLimitsConfiguration = null;

    private ?RateLimits $customerServerRestLimitsConfiguration = null;

    private int $defaultStorageTtlDays = 0;

    private int $maxAssets = 0;

    private int $maxCreatedAlarms = 0;

    private int $maxCustomers = 0;

    private int $maxDPStorageDays = 0;

    private int $maxDashboards = 0;

    private int $maxDevices = 0;

    private int $maxEmails = 0;

    private int $maxJSExecutions = 0;

    private int $maxOtaPackagesInBytes = 0;

    private int $maxREExecutions = 0;

    private int $maxResourcesInBytes = 0;

    private int $maxRuleChains = 0;

    private int $maxRuleNodeExecutionsPerMessage = 0;

    private int $maxSms = 0;

    private int $maxTransportDataPoints = 0;

    private int $maxTransportMessages = 0;

    private int $maxUsers = 0;

    private int $maxWsSessionsPerCustomer = 0;

    private int $maxWsSessionsPerPublicUser = 0;

    private int $maxWsSessionsPerRegularUser = 0;

    private int $maxWsSessionsPerTenant = 0;

    private int $maxWsSubscriptionsPerCustomer = 0;

    private int $maxWsSubscriptionsPerPublicUser = 0;

    private int $maxWsSubscriptionsPerRegularUser = 0;

    private int $maxWsSubscriptionsPerTenant = 0;

    private int $rpcTtlDays = 0;

    private ?RateLimits $tenantEntityExportRateLimit = null;

    private ?RateLimits $tenantEntityImportRateLimit = null;

    private ?RateLimits $tenantNotificationRequestsPerRuleRateLimit = null;

    private ?RateLimits $tenantNotificationRequestsRateLimit = null;

    private ?RateLimits $tenantServerRestLimitsConfiguration = null;

    private ?RateLimits $transportDeviceMsgRateLimit = null;

    private ?RateLimits $transportDeviceTelemetryDataPointsRateLimit = null;

    private ?RateLimits $transportDeviceTelemetryMsgRateLimit = null;

    private ?RateLimits $transportTenantMsgRateLimit = null;

    private ?RateLimits $transportTenantTelemetryDataPointsRateLimit = null;

    private ?RateLimits $transportTenantTelemetryMsgRateLimit = null;

    private string $type = 'DEFAULT';

    private int $warnThreshold = 0;

    private int $wsMsgQueueLimitPerSession = 0;

    private ?RateLimits $wsUpdatesPerSessionRateLimit = null;

    public function __construct(array $configurations = [])
    {
        foreach ($configurations as $k => $v) {
            $method = 'set'.ucfirst($k);
            $this->{$method}($v);
        }
    }

    public static function make(): Configuration
    {
        return new self;
    }

    public function toArray(): array
    {
        return [
            'alarmsTtlDays' => $this->alarmsTtlDays,
            'cassandraQueryTenantRateLimitsConfiguration' => (string) $this->cassandraQueryTenantRateLimitsConfiguration,
            'customerServerRestLimitsConfiguration' => (string) $this->customerServerRestLimitsConfiguration,
            'defaultStorageTtlDays' => $this->defaultStorageTtlDays,
            'maxAssets' => $this->maxAssets,
            'maxCreatedAlarms' => $this->maxCreatedAlarms,
            'maxCustomers' => $this->maxCustomers,
            'maxDPStorageDays' => $this->maxDPStorageDays,
            'maxDashboards' => $this->maxDashboards,
            'maxDevices' => $this->maxDevices,
            'maxEmails' => $this->maxEmails,
            'maxJSExecutions' => $this->maxJSExecutions,
            'maxOtaPackagesInBytes' => $this->maxOtaPackagesInBytes,
            'maxREExecutions' => $this->maxREExecutions,
            'maxResourcesInBytes' => $this->maxResourcesInBytes,
            'maxRuleChains' => $this->maxRuleChains,
            'maxRuleNodeExecutionsPerMessage' => $this->maxRuleNodeExecutionsPerMessage,
            'maxSms' => $this->maxSms,
            'maxTransportDataPoints' => $this->maxTransportDataPoints,
            'maxTransportMessages' => $this->maxTransportMessages,
            'maxUsers' => $this->maxUsers,
            'maxWsSessionsPerCustomer' => $this->maxWsSessionsPerCustomer,
            'maxWsSessionsPerPublicUser' => $this->maxWsSessionsPerPublicUser,
            'maxWsSessionsPerRegularUser' => $this->maxWsSessionsPerRegularUser,
            'maxWsSessionsPerTenant' => $this->maxWsSessionsPerTenant,
            'maxWsSubscriptionsPerCustomer' => $this->maxWsSubscriptionsPerCustomer,
            'maxWsSubscriptionsPerPublicUser' => $this->maxWsSubscriptionsPerPublicUser,
            'maxWsSubscriptionsPerRegularUser' => $this->maxWsSubscriptionsPerRegularUser,
            'maxWsSubscriptionsPerTenant' => $this->maxWsSubscriptionsPerTenant,
            'rpcTtlDays' => $this->rpcTtlDays,
            'tenantEntityExportRateLimit' => (string) $this->tenantEntityExportRateLimit,
            'tenantEntityImportRateLimit' => (string) $this->tenantEntityImportRateLimit,
            'tenantNotificationRequestsPerRuleRateLimit' => (string) $this->tenantNotificationRequestsPerRuleRateLimit,
            'tenantNotificationRequestsRateLimit' => (string) $this->tenantNotificationRequestsRateLimit,
            'tenantServerRestLimitsConfiguration' => (string) $this->tenantServerRestLimitsConfiguration,
            'transportDeviceMsgRateLimit' => (string) $this->transportDeviceMsgRateLimit,
            'transportDeviceTelemetryDataPointsRateLimit' => (string) $this->transportDeviceTelemetryDataPointsRateLimit,
            'transportDeviceTelemetryMsgRateLimit' => (string) $this->transportDeviceTelemetryMsgRateLimit,
            'transportTenantMsgRateLimit' => (string) $this->transportTenantMsgRateLimit,
            'transportTenantTelemetryDataPointsRateLimit' => (string) $this->transportTenantTelemetryDataPointsRateLimit,
            'transportTenantTelemetryMsgRateLimit' => (string) $this->transportTenantTelemetryMsgRateLimit,
            'type' => $this->type,
            'warnThreshold' => $this->warnThreshold,
            'wsMsgQueueLimitPerSession' => (string) $this->wsMsgQueueLimitPerSession,
            'wsUpdatesPerSessionRateLimit' => (string) $this->wsUpdatesPerSessionRateLimit,
        ];
    }

    public function getAlarmsTtlDays(): int
    {
        return $this->alarmsTtlDays;
    }

    public function getCassandraQueryTenantRateLimitsConfiguration(): ?RateLimits
    {
        return $this->cassandraQueryTenantRateLimitsConfiguration;
    }

    public function getCustomerServerRestLimitsConfiguration(): ?RateLimits
    {
        return $this->customerServerRestLimitsConfiguration;
    }

    public function getDefaultStorageTtlDays(): int
    {
        return $this->defaultStorageTtlDays;
    }

    public function getMaxAssets(): int
    {
        return $this->maxAssets;
    }

    public function getMaxCreatedAlarms(): int
    {
        return $this->maxCreatedAlarms;
    }

    public function getMaxCustomers(): int
    {
        return $this->maxCustomers;
    }

    public function getMaxDPStorageDays(): int
    {
        return $this->maxDPStorageDays;
    }

    public function getMaxDashboards(): int
    {
        return $this->maxDashboards;
    }

    public function getMaxDevices(): int
    {
        return $this->maxDevices;
    }

    public function getMaxEmails(): int
    {
        return $this->maxEmails;
    }

    public function getMaxJSExecutions(): int
    {
        return $this->maxJSExecutions;
    }

    public function getMaxOtaPackagesInBytes(): int
    {
        return $this->maxOtaPackagesInBytes;
    }

    public function getMaxREExecutions(): int
    {
        return $this->maxREExecutions;
    }

    public function getMaxResourcesInBytes(): int
    {
        return $this->maxResourcesInBytes;
    }

    public function getMaxRuleChains(): int
    {
        return $this->maxRuleChains;
    }

    public function getMaxRuleNodeExecutionsPerMessage(): int
    {
        return $this->maxRuleNodeExecutionsPerMessage;
    }

    public function getMaxSms(): int
    {
        return $this->maxSms;
    }

    public function getMaxTransportDataPoints(): int
    {
        return $this->maxTransportDataPoints;
    }

    public function getMaxTransportMessages(): int
    {
        return $this->maxTransportMessages;
    }

    public function getMaxUsers(): int
    {
        return $this->maxUsers;
    }

    public function getMaxWsSessionsPerCustomer(): int
    {
        return $this->maxWsSessionsPerCustomer;
    }

    public function getMaxWsSessionsPerPublicUser(): int
    {
        return $this->maxWsSessionsPerPublicUser;
    }

    public function getMaxWsSessionsPerRegularUser(): int
    {
        return $this->maxWsSessionsPerRegularUser;
    }

    public function getMaxWsSessionsPerTenant(): int
    {
        return $this->maxWsSessionsPerTenant;
    }

    public function getMaxWsSubscriptionsPerCustomer(): int
    {
        return $this->maxWsSubscriptionsPerCustomer;
    }

    public function getMaxWsSubscriptionsPerPublicUser(): int
    {
        return $this->maxWsSubscriptionsPerPublicUser;
    }

    public function getMaxWsSubscriptionsPerRegularUser(): int
    {
        return $this->maxWsSubscriptionsPerRegularUser;
    }

    public function getMaxWsSubscriptionsPerTenant(): int
    {
        return $this->maxWsSubscriptionsPerTenant;
    }

    public function getRpcTtlDays(): int
    {
        return $this->rpcTtlDays;
    }

    public function getTenantEntityExportRateLimit(): ?RateLimits
    {
        return $this->tenantEntityExportRateLimit;
    }

    public function getTenantEntityImportRateLimit(): ?RateLimits
    {
        return $this->tenantEntityImportRateLimit;
    }

    public function getTenantNotificationRequestsPerRuleRateLimit(): ?RateLimits
    {
        return $this->tenantNotificationRequestsPerRuleRateLimit;
    }

    public function getTenantNotificationRequestsRateLimit(): ?RateLimits
    {
        return $this->tenantNotificationRequestsRateLimit;
    }

    public function getTenantServerRestLimitsConfiguration(): ?RateLimits
    {
        return $this->tenantServerRestLimitsConfiguration;
    }

    public function getTransportDeviceMsgRateLimit(): ?RateLimits
    {
        return $this->transportDeviceMsgRateLimit;
    }

    public function getTransportDeviceTelemetryDataPointsRateLimit(): ?RateLimits
    {
        return $this->transportDeviceTelemetryDataPointsRateLimit;
    }

    public function getTransportDeviceTelemetryMsgRateLimit(): ?RateLimits
    {
        return $this->transportDeviceTelemetryMsgRateLimit;
    }

    public function getTransportTenantMsgRateLimit(): ?RateLimits
    {
        return $this->transportTenantMsgRateLimit;
    }

    public function getTransportTenantTelemetryDataPointsRateLimit(): ?RateLimits
    {
        return $this->transportTenantTelemetryDataPointsRateLimit;
    }

    public function getTransportTenantTelemetryMsgRateLimit(): ?RateLimits
    {
        return $this->transportTenantTelemetryMsgRateLimit;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getWarnThreshold(): int
    {
        return $this->warnThreshold;
    }

    public function getWsMsgQueueLimitPerSession(): int
    {
        return $this->wsMsgQueueLimitPerSession;
    }

    public function getWsUpdatesPerSessionRateLimit(): ?RateLimits
    {
        return $this->wsUpdatesPerSessionRateLimit;
    }

    public function setAlarmsTtlDays(int $alarmsTtlDays): static
    {
        return tap($this, fn () => $this->alarmsTtlDays = $alarmsTtlDays);
    }

    public function setCassandraQueryTenantRateLimitsConfiguration(RateLimits|string|null $cassandraQueryTenantRateLimitsConfiguration): static
    {
        $cassandraQueryTenantRateLimitsConfiguration = is_string($cassandraQueryTenantRateLimitsConfiguration)
            ? RateLimits::fromString($cassandraQueryTenantRateLimitsConfiguration)
            : $cassandraQueryTenantRateLimitsConfiguration;

        return tap($this, fn () => $this->cassandraQueryTenantRateLimitsConfiguration = $cassandraQueryTenantRateLimitsConfiguration);
    }

    public function setCustomerServerRestLimitsConfiguration(RateLimits|string|null $customerServerRestLimitsConfiguration): static
    {
        $customerServerRestLimitsConfiguration = is_string($customerServerRestLimitsConfiguration)
            ? RateLimits::fromString($customerServerRestLimitsConfiguration)
            : $customerServerRestLimitsConfiguration;

        return tap($this, fn () => $this->customerServerRestLimitsConfiguration = $customerServerRestLimitsConfiguration);
    }

    public function setDefaultStorageTtlDays(int $defaultStorageTtlDays): static
    {
        return tap($this, fn () => $this->defaultStorageTtlDays = $defaultStorageTtlDays);
    }

    public function setMaxAssets(int $maxAssets): static
    {
        return tap($this, fn () => $this->maxAssets = $maxAssets);
    }

    public function setMaxCreatedAlarms(int $maxCreatedAlarms): static
    {
        return tap($this, fn () => $this->maxCreatedAlarms = $maxCreatedAlarms);
    }

    public function setMaxCustomers(int $maxCustomers): static
    {
        return tap($this, fn () => $this->maxCustomers = $maxCustomers);
    }

    public function setMaxDPStorageDays(int $maxDPStorageDays): static
    {
        return tap($this, fn () => $this->maxDPStorageDays = $maxDPStorageDays);
    }

    public function setMaxDashboards(int $maxDashboards): static
    {
        return tap($this, fn () => $this->maxDashboards = $maxDashboards);
    }

    public function setMaxDevices(int $maxDevices): static
    {
        return tap($this, fn () => $this->maxDevices = $maxDevices);
    }

    public function setMaxEmails(int $maxEmails): static
    {
        return tap($this, fn () => $this->maxEmails = $maxEmails);
    }

    public function setMaxJSExecutions(int $maxJSExecutions): static
    {
        return tap($this, fn () => $this->maxJSExecutions = $maxJSExecutions);
    }

    public function setMaxOtaPackagesInBytes(int $maxOtaPackagesInBytes): static
    {
        return tap($this, fn () => $this->maxOtaPackagesInBytes = $maxOtaPackagesInBytes);
    }

    public function setMaxREExecutions(int $maxREExecutions): static
    {
        return tap($this, fn () => $this->maxREExecutions = $maxREExecutions);
    }

    public function setMaxResourcesInBytes(int $maxResourcesInBytes): static
    {
        return tap($this, fn () => $this->maxResourcesInBytes = $maxResourcesInBytes);
    }

    public function setMaxRuleChains(int $maxRuleChains): static
    {
        return tap($this, fn () => $this->maxRuleChains = $maxRuleChains);
    }

    public function setMaxRuleNodeExecutionsPerMessage(int $maxRuleNodeExecutionsPerMessage): static
    {
        return tap($this, fn () => $this->maxRuleNodeExecutionsPerMessage = $maxRuleNodeExecutionsPerMessage);
    }

    public function setMaxSms(int $maxSms): static
    {
        return tap($this, fn () => $this->maxSms = $maxSms);
    }

    public function setMaxTransportDataPoints(int $maxTransportDataPoints): static
    {
        return tap($this, fn () => $this->maxTransportDataPoints = $maxTransportDataPoints);
    }

    public function setMaxTransportMessages(int $maxTransportMessages): static
    {
        return tap($this, fn () => $this->maxTransportMessages = $maxTransportMessages);
    }

    public function setMaxUsers(int $maxUsers): static
    {
        return tap($this, fn () => $this->maxUsers = $maxUsers);
    }

    public function setMaxWsSessionsPerCustomer(int $maxWsSessionsPerCustomer): static
    {
        return tap($this, fn () => $this->maxWsSessionsPerCustomer = $maxWsSessionsPerCustomer);
    }

    public function setMaxWsSessionsPerPublicUser(int $maxWsSessionsPerPublicUser): static
    {
        return tap($this, fn () => $this->maxWsSessionsPerPublicUser = $maxWsSessionsPerPublicUser);
    }

    public function setMaxWsSessionsPerRegularUser(int $maxWsSessionsPerRegularUser): static
    {
        return tap($this, fn () => $this->maxWsSessionsPerRegularUser = $maxWsSessionsPerRegularUser);
    }

    public function setMaxWsSessionsPerTenant(int $maxWsSessionsPerTenant): static
    {
        return tap($this, fn () => $this->maxWsSessionsPerTenant = $maxWsSessionsPerTenant);
    }

    public function setMaxWsSubscriptionsPerCustomer(int $maxWsSubscriptionsPerCustomer): static
    {
        return tap($this, fn () => $this->maxWsSubscriptionsPerCustomer = $maxWsSubscriptionsPerCustomer);
    }

    public function setMaxWsSubscriptionsPerPublicUser(int $maxWsSubscriptionsPerPublicUser): static
    {
        return tap($this, fn () => $this->maxWsSubscriptionsPerPublicUser = $maxWsSubscriptionsPerPublicUser);
    }

    public function setMaxWsSubscriptionsPerRegularUser(int $maxWsSubscriptionsPerRegularUser): static
    {
        return tap($this, fn () => $this->maxWsSubscriptionsPerRegularUser = $maxWsSubscriptionsPerRegularUser);
    }

    public function setMaxWsSubscriptionsPerTenant(int $maxWsSubscriptionsPerTenant): static
    {
        return tap($this, fn () => $this->maxWsSubscriptionsPerTenant = $maxWsSubscriptionsPerTenant);
    }

    public function setRpcTtlDays(int $rpcTtlDays): static
    {
        return tap($this, fn () => $this->rpcTtlDays = $rpcTtlDays);
    }

    public function setTenantEntityExportRateLimit(RateLimits|string|null $tenantEntityExportRateLimit): static
    {
        $tenantEntityExportRateLimit = is_string($tenantEntityExportRateLimit)
            ? RateLimits::fromString($tenantEntityExportRateLimit)
            : $tenantEntityExportRateLimit;

        return tap($this, fn () => $this->tenantEntityExportRateLimit = $tenantEntityExportRateLimit);
    }

    public function setTenantEntityImportRateLimit(RateLimits|string|null $tenantEntityImportRateLimit): static
    {
        $tenantEntityImportRateLimit = is_string($tenantEntityImportRateLimit)
            ? RateLimits::fromString($tenantEntityImportRateLimit)
            : $tenantEntityImportRateLimit;

        return tap($this, fn () => $this->tenantEntityImportRateLimit = $tenantEntityImportRateLimit);
    }

    public function setTenantNotificationRequestsPerRuleRateLimit(RateLimits|string|null $tenantNotificationRequestsPerRuleRateLimit): static
    {
        $tenantNotificationRequestsPerRuleRateLimit = is_string($tenantNotificationRequestsPerRuleRateLimit)
            ? RateLimits::fromString($tenantNotificationRequestsPerRuleRateLimit)
            : $tenantNotificationRequestsPerRuleRateLimit;

        return tap($this, fn () => $this->tenantNotificationRequestsPerRuleRateLimit = $tenantNotificationRequestsPerRuleRateLimit);
    }

    public function setTenantNotificationRequestsRateLimit(RateLimits|string|null $tenantNotificationRequestsRateLimit): static
    {
        $tenantNotificationRequestsRateLimit = is_string($tenantNotificationRequestsRateLimit)
            ? RateLimits::fromString($tenantNotificationRequestsRateLimit)
            : $tenantNotificationRequestsRateLimit;

        return tap($this, fn () => $this->tenantNotificationRequestsRateLimit = $tenantNotificationRequestsRateLimit);
    }

    public function setTenantServerRestLimitsConfiguration(RateLimits|string|null $tenantServerRestLimitsConfiguration): static
    {
        $tenantServerRestLimitsConfiguration = is_string($tenantServerRestLimitsConfiguration)
            ? RateLimits::fromString($tenantServerRestLimitsConfiguration)
            : $tenantServerRestLimitsConfiguration;

        return tap($this, fn () => $this->tenantServerRestLimitsConfiguration = $tenantServerRestLimitsConfiguration);
    }

    public function setTransportDeviceMsgRateLimit(RateLimits|string|null $transportDeviceMsgRateLimit): static
    {
        $transportDeviceMsgRateLimit = is_string($transportDeviceMsgRateLimit)
            ? RateLimits::fromString($transportDeviceMsgRateLimit)
            : $transportDeviceMsgRateLimit;

        return tap($this, fn () => $this->transportDeviceMsgRateLimit = $transportDeviceMsgRateLimit);
    }

    public function setTransportDeviceTelemetryDataPointsRateLimit(RateLimits|string|null $transportDeviceTelemetryDataPointsRateLimit): static
    {
        $transportDeviceTelemetryDataPointsRateLimit = is_string($transportDeviceTelemetryDataPointsRateLimit)
            ? RateLimits::fromString($transportDeviceTelemetryDataPointsRateLimit)
            : $transportDeviceTelemetryDataPointsRateLimit;

        return tap($this, fn () => $this->transportDeviceTelemetryDataPointsRateLimit = $transportDeviceTelemetryDataPointsRateLimit);
    }

    public function setTransportDeviceTelemetryMsgRateLimit(RateLimits|string|null $transportDeviceTelemetryMsgRateLimit): static
    {
        $transportDeviceTelemetryMsgRateLimit = is_string($transportDeviceTelemetryMsgRateLimit)
            ? RateLimits::fromString($transportDeviceTelemetryMsgRateLimit)
            : $transportDeviceTelemetryMsgRateLimit;

        return tap($this, fn () => $this->transportDeviceTelemetryMsgRateLimit = $transportDeviceTelemetryMsgRateLimit);
    }

    public function setTransportTenantMsgRateLimit(RateLimits|string|null $transportTenantMsgRateLimit): static
    {
        $transportTenantMsgRateLimit = is_string($transportTenantMsgRateLimit)
            ? RateLimits::fromString($transportTenantMsgRateLimit)
            : $transportTenantMsgRateLimit;

        return tap($this, fn () => $this->transportTenantMsgRateLimit = $transportTenantMsgRateLimit);
    }

    public function setTransportTenantTelemetryDataPointsRateLimit(RateLimits|string|null $transportTenantTelemetryDataPointsRateLimit): static
    {
        $transportTenantTelemetryDataPointsRateLimit = is_string($transportTenantTelemetryDataPointsRateLimit)
            ? RateLimits::fromString($transportTenantTelemetryDataPointsRateLimit)
            : $transportTenantTelemetryDataPointsRateLimit;

        return tap($this, fn () => $this->transportTenantTelemetryDataPointsRateLimit = $transportTenantTelemetryDataPointsRateLimit);
    }

    public function setTransportTenantTelemetryMsgRateLimit(RateLimits|string|null $transportTenantTelemetryMsgRateLimit): static
    {
        $transportTenantTelemetryMsgRateLimit = is_string($transportTenantTelemetryMsgRateLimit)
            ? RateLimits::fromString($transportTenantTelemetryMsgRateLimit)
            : $transportTenantTelemetryMsgRateLimit;

        return tap($this, fn () => $this->transportTenantTelemetryMsgRateLimit = $transportTenantTelemetryMsgRateLimit);
    }

    public function setType(string $type): static
    {
        return tap($this, fn () => $this->type = $type);
    }

    public function setWarnThreshold(int $warnThreshold): static
    {
        return tap($this, fn () => $this->warnThreshold = $warnThreshold);
    }

    public function setWsMsgQueueLimitPerSession(int $wsMsgQueueLimitPerSession): static
    {
        return tap($this, fn () => $this->wsMsgQueueLimitPerSession = $wsMsgQueueLimitPerSession);
    }

    public function setWsUpdatesPerSessionRateLimit(RateLimits|string|null $wsUpdatesPerSessionRateLimit): static
    {
        $wsUpdatesPerSessionRateLimit = is_string($wsUpdatesPerSessionRateLimit)
            ? RateLimits::fromString($wsUpdatesPerSessionRateLimit)
            : $wsUpdatesPerSessionRateLimit;

        return tap($this, fn () => $this->wsUpdatesPerSessionRateLimit = $wsUpdatesPerSessionRateLimit);
    }
}
