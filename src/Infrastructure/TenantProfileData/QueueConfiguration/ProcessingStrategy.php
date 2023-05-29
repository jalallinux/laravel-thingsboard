<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class ProcessingStrategy
{
    public int $failurePercentage;

    public int $maxPauseBetweenRetries;

    public int $pauseBetweenRetries;

    public int $retries;

    public string $type;

    public static function fromArray(array $processingStrategy): ?static
    {
        $instance = self::make();
        if (empty($processingStrategy)) {
            return null;
        }
        foreach ($processingStrategy as $key => $value) {
            $method = 'set'.ucfirst($key);
            $instance->{$method}($value);
        }

        return $instance;
    }

    public static function make(): ProcessingStrategy
    {
        return new self;
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
            '$failurePercentage = this->type',
            'maxPauseBetweenRetries' => $this->type,
            'pauseBetweenRetries' => $this->type,
            'retries' => $this->type,
            'type' => $this->type,
        ];
    }
}
