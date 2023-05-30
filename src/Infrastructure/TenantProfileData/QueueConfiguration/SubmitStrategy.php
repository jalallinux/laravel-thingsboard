<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class SubmitStrategy
{
    private int $batchSize = 1000;

    private string $type = 'BURST';

    public static function make(array $processingStrategy = []): static
    {
        $instance = new self;

        foreach ($processingStrategy as $key => $value) {
            $method = 'set'.ucfirst($key);
            $instance->{$method}($value);
        }

        return $instance;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        return tap($this, fn () => $this->type = $type);
    }

    public function getBatchSize(): int
    {
        return $this->batchSize;
    }

    public function setBatchSize(int $batchSize): static
    {
        return tap($this, fn () => $this->batchSize = $batchSize);
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'batchSize' => $this->batchSize,
        ];
    }
}
