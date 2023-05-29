<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class QueueConfiguration
{
    public AdditionalInfo $additionalInfo;

    public bool $consumerPerPartition;

    public string $name;

    public int $packProcessingTimeout;

    public int $partitions;

    public int $pollInterval;

    public ProcessingStrategy $processingStrategy;

    public SubmitStrategy $submitStrategy;

    public string $topic;

    public function __construct(array $queueConfiguration = [])
    {
        foreach ($queueConfiguration as $k => $v) {
            $method = 'set'.ucfirst($k);
            $this->{$method}($v);
        }
    }

    public static function make(): QueueConfiguration
    {
        return new self;
    }

    public function toArray(): array
    {
        return [
            'additionalInfo' => $this->additionalInfo->toArray(),
            'consumerPerPartition' => $this->consumerPerPartition,
            'name' => $this->name,
            'packProcessingTimeout' => $this->packProcessingTimeout,
            'partitions' => $this->partitions,
            'pollInterval' => $this->pollInterval,
            'processingStrategy' => $this->processingStrategy->toArray(),
            'submitStrategy' => $this->submitStrategy->toArray(),
            'topic' => $this->topic,
        ];
    }

    public function getAdditionalInfo(): AdditionalInfo
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(AdditionalInfo|array|null $additionalInfo): static
    {
        $additionalInfo = is_array($additionalInfo) ? AdditionalInfo::fromArray($additionalInfo) : $additionalInfo;

        return tap($this, fn () => $this->additionalInfo = $additionalInfo);
    }

    public function isConsumerPerPartition(): bool
    {
        return $this->consumerPerPartition;
    }

    public function setConsumerPerPartition(bool $consumerPerPartition): static
    {
        return tap($this, fn () => $this->consumerPerPartition = $consumerPerPartition);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        return tap($this, fn () => $this->name = $name);
    }

    public function getPackProcessingTimeout(): int
    {
        return $this->packProcessingTimeout;
    }

    public function setPackProcessingTimeout(int $packProcessingTimeout): static
    {
        return tap($this, fn () => $this->packProcessingTimeout = $packProcessingTimeout);
    }

    public function getPartitions(): int
    {
        return $this->partitions;
    }

    public function setPartitions(int $partitions): static
    {
        return tap($this, fn () => $this->partitions = $partitions);
    }

    public function getPollInterval(): int
    {
        return $this->pollInterval;
    }

    public function setPollInterval(int $pollInterval): static
    {
        return tap($this, fn () => $this->pollInterval = $pollInterval);
    }

    public function getProcessingStrategy(): ProcessingStrategy
    {
        return $this->processingStrategy;
    }

    public function setProcessingStrategy(ProcessingStrategy|array|null $processingStrategy): static
    {
        $processingStrategy = is_array($processingStrategy) ? ProcessingStrategy::fromArray($processingStrategy) : $processingStrategy;

        return tap($this, fn () => $this->processingStrategy = $processingStrategy);
    }

    public function getSubmitStrategy(): SubmitStrategy
    {
        return $this->submitStrategy;
    }

    public function setSubmitStrategy(SubmitStrategy|array|null $submitStrategy): static
    {
        $submitStrategy = is_array($submitStrategy) ? SubmitStrategy::fromArray($submitStrategy) : $submitStrategy;

        return tap($this, fn () => $this->submitStrategy = $submitStrategy);
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): static
    {
        return tap($this, fn () => $this->topic = $topic);
    }
}
