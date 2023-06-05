<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class ProcessingStrategy
{
    private int $failurePercentage = 0;

    private int $maxPauseBetweenRetries = 3;

    private int $pauseBetweenRetries = 3;

    private int $retries = 3;

    private string $type = 'SKIP_ALL_FAILURES';

    public static function make(array $processingStrategy = []): static
    {
        $instance = new self;

        foreach ($processingStrategy as $key => $value) {
            $method = 'set'.ucfirst($key);
            $instance->{$method}($value);
        }

        return $instance;
    }

    public function getFailurePercentage(): int
    {
        return $this->failurePercentage;
    }

    public function setFailurePercentage(int $failurePercentage): static
    {
        return tap($this, fn () => $this->failurePercentage = $failurePercentage);
    }

    public function getMaxPauseBetweenRetries(): int
    {
        return $this->maxPauseBetweenRetries;
    }

    public function setMaxPauseBetweenRetries(int $maxPauseBetweenRetries): static
    {
        return tap($this, fn () => $this->maxPauseBetweenRetries = $maxPauseBetweenRetries);
    }

    public function getPauseBetweenRetries(): int
    {
        return $this->pauseBetweenRetries;
    }

    public function setPauseBetweenRetries(int $pauseBetweenRetries): static
    {
        return tap($this, fn () => $this->pauseBetweenRetries = $pauseBetweenRetries);
    }

    public function getRetries(): int
    {
        return $this->retries;
    }

    public function setRetries(int $retries): static
    {
        return tap($this, fn () => $this->retries = $retries);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        return tap($this, fn () => $this->type = $type);
    }

    public function toArray(): array
    {
        return [
            'failurePercentage' => $this->getFailurePercentage(),
            'maxPauseBetweenRetries' => $this->getMaxPauseBetweenRetries(),
            'pauseBetweenRetries' => $this->getPauseBetweenRetries(),
            'retries' => $this->getRetries(),
            'type' => $this->getType(),
        ];
    }
}
