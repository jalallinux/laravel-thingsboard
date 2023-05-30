<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\Configuration;

use Illuminate\Support\Collection;

class RateLimits
{
    private array $rateLimits = [];

    public function __construct(array $rateLimits = [])
    {
        if (! empty($rateLimits)) {
            $this->import($rateLimits);
        }
    }

    public static function make(array $rateLimits = []): static
    {
        return new self($rateLimits);
    }

    public function add(int $messageCount, int $second, bool $flush = false): static
    {
        if ($flush) {
            $this->rateLimits = [];
        }

        return tap($this, fn () => $this->rateLimits[] = [$messageCount, $second]);
    }

    public function import(array $rateLimits, bool $flush = false): static
    {
        if ($flush) {
            $this->rateLimits = [];
        }

        foreach ($rateLimits as $messageCount => $second) {
            if (is_numeric($messageCount) && is_numeric($second)) {
                $this->add($messageCount, $second);
            }
        }

        return $this;
    }

    public static function fromString(string $rateLimits): static
    {
        $instance = self::make();
        foreach (array_filter(explode(',', $rateLimits)) as $rateLimit) {
            $instance->add(explode(':', $rateLimit)[0], explode(':', $rateLimit)[1]);
        }

        return $instance;
    }

    public function __toString(): string
    {
        return implode(',', array_map(fn ($rateLimit) => "{$rateLimit[0]}:{$rateLimit[1]}", $this->rateLimits));
    }

    public function collect(): Collection
    {
        return collect($this->rateLimits);
    }
}
