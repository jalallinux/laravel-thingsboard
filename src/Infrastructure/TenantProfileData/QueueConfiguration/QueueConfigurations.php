<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class QueueConfigurations
{
    public ?array $queueConfigurations = null;

    public function __construct(?array $queueConfigurations = [])
    {
        if (is_null($queueConfigurations)) {
            return $this->queueConfigurations;
        }
        foreach ($queueConfigurations as $queueConfiguration) {
            $this->queueConfigurations[] = new QueueConfiguration($queueConfiguration);
        }
    }

    public function toArray(): array
    {
        return $this->queueConfigurations;
    }
}
