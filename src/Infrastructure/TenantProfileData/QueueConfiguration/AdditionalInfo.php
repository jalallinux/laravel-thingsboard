<?php

namespace JalalLinuX\Thingsboard\Infrastructure\TenantProfileData\QueueConfiguration;

class AdditionalInfo
{
    public string $description = '';

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
        ];
    }

    public static function fromArray(array $additionalInfo): ?static
    {
        $instance = self::make();
        if (empty($additionalInfo)) {
            return null;
        }
        foreach ($additionalInfo as $key => $value) {
            $method = 'set'.ucfirst($key);
            $instance->{$method}($value);
        }

        return $instance;
    }

    public static function make(): AdditionalInfo
    {
        return new self;
    }
}
