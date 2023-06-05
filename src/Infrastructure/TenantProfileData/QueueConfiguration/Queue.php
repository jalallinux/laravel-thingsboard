<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

use JalalLinuX\Thingsboard\Exceptions\Exception;

class Queue
{
    private ?array $additionalInfo = null;

    private bool $consumerPerPartition = true;

    private string $name;

    private int $packProcessingTimeout = 2000;

    private int $partitions = 10;

    private int $pollInterval = 25;

    private ProcessingStrategy $processingStrategy;

    private SubmitStrategy $submitStrategy;

    private string $topic = 'tb_rule_engine.main';

    public function __construct(array $configuration = [])
    {
        throw_if(! array_key_exists('name', $configuration), new Exception('Queue configuration must have name key.'));

        $this->setProcessingStrategy(ProcessingStrategy::make())->setSubmitStrategy(SubmitStrategy::make());

        if (! empty($configuration)) {
            foreach ($configuration as $k => $v) {
                $method = 'set'.ucfirst($k);
                $this->{$method}($v);
            }
        }
    }

    public static function make(string $name, array $configuration = []): static
    {
        return new self(array_merge($configuration, ['name' => $name]));
    }

    public function toArray(): array
    {
        return [
            'additionalInfo' => $this->getAdditionalInfo(),
            'consumerPerPartition' => $this->getConsumerPerPartition(),
            'name' => $this->getName(),
            'packProcessingTimeout' => $this->getPackProcessingTimeout(),
            'partitions' => $this->getPartitions(),
            'pollInterval' => $this->getPollInterval(),
            'processingStrategy' => $this->getProcessingStrategy()->toArray(),
            'submitStrategy' => $this->getSubmitStrategy()->toArray(),
            'topic' => $this->getTopic(),
        ];
    }

    public function getAdditionalInfo(): ?array
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(array $additionalInfo = null): static
    {
        return tap($this, fn () => $this->additionalInfo = $additionalInfo);
    }

    public function getConsumerPerPartition(): bool
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
        $processingStrategy = is_array($processingStrategy) ? ProcessingStrategy::make($processingStrategy) : $processingStrategy;

        return tap($this, fn () => $this->processingStrategy = $processingStrategy);
    }

    public function getSubmitStrategy(): SubmitStrategy
    {
        return $this->submitStrategy;
    }

    public function setSubmitStrategy(SubmitStrategy|array|null $submitStrategy): static
    {
        $submitStrategy = is_array($submitStrategy) ? SubmitStrategy::make($submitStrategy) : $submitStrategy;

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
