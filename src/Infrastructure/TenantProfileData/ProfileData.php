<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData;

class ProfileData
{
    private Configuration $configuration;

    private ?array $queueConfiguration;

    public function __construct(Configuration $configuration, array $queueConfiguration = null)
    {
        $this->setConfiguration($configuration)->setQueueConfiguration($queueConfiguration);
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function setConfiguration(Configuration $configuration): static
    {
        return tap($this, fn () => $this->configuration = $configuration);
    }

    public function getQueueConfiguration(): array
    {
        return $this->queueConfiguration;
    }

    public function setQueueConfiguration(array $queueConfiguration = null): static
    {
        return tap($this, fn () => $this->queueConfiguration = $queueConfiguration);
    }

    public function toArray(): array
    {
        return [
            'configuration' => $this->configuration->toArray(),
            'queueConfiguration' => $this->queueConfiguration,
        ];
    }
}
