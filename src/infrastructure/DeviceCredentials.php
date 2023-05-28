<?php

namespace JalalLinuX\Thingsboard\infrastructure;

use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Enums\EnumDeviceCredentialsType;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;

class DeviceCredentials
{
    private string $id;
    private Id $deviceId;
    private Carbon $createdTime;
    private EnumDeviceCredentialsType $credentialsType;
    private string $credentialsId;
    private ?string $credentialsValue;

    public function __construct(array $deviceCredentials)
    {
        $this->setId($deviceCredentials['id']['id'])
            ->setDeviceId(new Id($deviceCredentials['deviceId']['id'], EnumEntityType::DEVICE()))
            ->setCreatedTime(Carbon::createFromTimestampMs($deviceCredentials['createdTime']))
            ->setCredentialsType(EnumDeviceCredentialsType::from($deviceCredentials['credentialsType']))
            ->setCredentialsId($deviceCredentials['credentialsId'])
            ->setCredentialsValue($deviceCredentials['credentialsValue']);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        return tap($this, fn() => $this->id = $id);
    }

    public function deviceId(): Id
    {
        return $this->deviceId;
    }

    public function setDeviceId(Id $deviceId): self
    {
        return tap($this, fn() => $this->deviceId = $deviceId);
    }

    public function createdTime(): Carbon
    {
        return $this->createdTime;
    }

    public function setCreatedTime(Carbon $createdTime): self
    {
        return tap($this, fn() => $this->createdTime = $createdTime);
    }

    public function credentialsType(): EnumDeviceCredentialsType
    {
        return $this->credentialsType;
    }

    public function setCredentialsType(EnumDeviceCredentialsType $credentialsType): self
    {
        return tap($this, fn() => $this->credentialsType = $credentialsType);
    }

    public function credentialsId(): string
    {
        return $this->credentialsId;
    }

    public function setCredentialsId(string $credentialsId): self
    {
        if ($this->credentialsType->equals(EnumDeviceCredentialsType::ACCESS_TOKEN()) && strlen($credentialsId) > 32) {
            throw new \Exception("CredentialsId must be less than 32 character when CredentialsType is " . EnumDeviceCredentialsType::ACCESS_TOKEN()->value);
        }
        return tap($this, fn() => $this->credentialsId = $credentialsId);
    }

    public function credentialsValue(): ?string
    {
        return $this->credentialsValue;
    }

    public function setCredentialsValue(?string $credentialsValue): self
    {
        return tap($this, fn() => $this->credentialsValue = $credentialsValue);
    }

    public function toArray(): array
    {
        return [
            'id' => ['id' => $this->id],
            'deviceId' => $this->deviceId->toArray(),
            'createdTime' => (int) $this->createdTime->getPreciseTimestamp(3),
            'credentialsType' => $this->credentialsType->value,
            'credentialsId' => $this->credentialsId,
            'credentialsValue' => $this->credentialsValue,
        ];
    }
}
