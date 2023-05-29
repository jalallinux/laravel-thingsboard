<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class QueueConfiguration
{
    private array $queues = [];

    public function __construct(array $queueConfigurations = [])
    {
        foreach ($queueConfigurations as $queueConfiguration) {
            $this->add(new Queue($queueConfiguration));
        }
    }

    public function add(Queue $queue, bool $flush = false): static
    {
        if ($flush) {
            $this->queues = [];
        }

        return tap($this, fn() => $this->queues[] = $queue);
    }

    public function setQueues(array $queues, bool $flush = false): static
    {
        if ($flush) {
            $this->queues = [];
        }

        foreach ($queues as $queue) {
            $this->add(new Queue($queue));
        }

        return $this;
    }

    public function getQueues(): array
    {
        return $this->queues;
    }

    public function toArray(): array
    {
        return array_map(fn(Queue $queue) => $queue->toArray(), $this->queues);
    }
}
