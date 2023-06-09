<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData;

use Illuminate\Contracts\Support\Arrayable;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\Configuration\Configuration;
use JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration\QueueConfiguration;
use JsonSerializable;

class ProfileData implements Arrayable, JsonSerializable
{
    private Configuration $configuration;

    private ?QueueConfiguration $queueConfiguration;

    public function __construct(Configuration $configuration, QueueConfiguration $queueConfigurations = null)
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

    public function getQueueConfiguration(): ?QueueConfiguration
    {
        return $this->queueConfiguration;
    }

    public function setQueueConfiguration(QueueConfiguration $queueConfiguration = null): static
    {
        return tap($this, fn () => $this->queueConfiguration = $queueConfiguration);
    }

    public function toArray(): array
    {
        return [
            'configuration' => $this->getConfiguration()->toArray(),
            'queueConfiguration' => is_null($this->getQueueConfiguration()) ? null : $this->queueConfiguration->toArray(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
