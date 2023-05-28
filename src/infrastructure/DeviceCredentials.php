<?php

namespace JalalLinuX\Thingsboard\infrastructure;

use Illuminate\Support\Carbon;
use JalalLinuX\Thingsboard\Enums\EnumDeviceCredentialsType;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use Spatie\Enum\Enum;

class DeviceCredentials
{
    private array $deviceCredentials;

    public function __construct(array $deviceCredentials)
    {
        $this->deviceCredentials = $deviceCredentials;
    }

    public function credentialsValue()
    {
        return $this->deviceCredentials['credentialsValue'];
    }

    public function credentialsId()
    {
        return $this->deviceCredentials['credentialsId'];
    }

    public function credentialsType(): Enum|EnumDeviceCredentialsType
    {
        return EnumDeviceCredentialsType::from($this->deviceCredentials['credentialsType']);
    }

    public function deviceId(): Id
    {
        return new Id($this->deviceCredentials['id']['id'], EnumEntityType::DEVICE());
    }

    public function createdTime(): Carbon
    {
        return Carbon::createFromTimestampMs($this->deviceCredentials['createdTime']);
    }

    public function id(): string
    {
        return $this->deviceCredentials['id']['id'];
    }

    public function toArray(): array
    {
        return $this->deviceCredentials;
    }
}
