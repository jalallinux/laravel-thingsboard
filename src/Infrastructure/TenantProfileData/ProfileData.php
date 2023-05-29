<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData;

use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\Configuration\Configuration;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration\QueueConfigurations;

class ProfileData
{
    private Configuration $configuration;

    private ?QueueConfigurations $queueConfiguration;

    public function __construct(Configuration $configuration, QueueConfigurations $queueConfigurations = null)
    {
        $this->setConfiguration($configuration)->setQueueConfiguration($queueConfigurations);
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function setConfiguration(Configuration $configuration): static
    {
        return tap($this, fn () => $this->configuration = $configuration);
    }

    public function getQueueConfiguration(): QueueConfigurations
    {
        return $this->queueConfiguration;
    }

    public function setQueueConfiguration(QueueConfigurations $queueConfiguration = null): static
    {
        return tap($this, fn () => $this->queueConfiguration = $queueConfiguration);
    }

    public function toArray(): array
    {
        return [
            'configuration' => $this->configuration->toArray(),
            'queueConfiguration' => $this->queueConfiguration->toArray(),
        ];
    }
}
