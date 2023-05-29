<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class SubmitStrategy
{
    public string $type;

    public int $batchSize;

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

    public static function make(): SubmitStrategy
    {
        return new self;
    }

    public static function fromArray(array $SubmitStrategy): ?static
    {
        $instance = self::make();
        if (empty($SubmitStrategy)) {
            return null;
        }
        foreach ($SubmitStrategy as $key => $value) {
            $method = 'set'.ucfirst($key);
            $instance->{$method}($value);
        }

        return $instance;
    }
}
