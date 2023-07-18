<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Thingsboard;
use JsonSerializable;

class Id implements Arrayable, JsonSerializable
{
    public string $id;

    public EnumEntityType $entityType;

    public function __construct(string $id, string $entityType)
    {
        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'id']);

        $this->id = $id;
        $this->entityType = EnumEntityType::from($entityType);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'entityType' => $this->entityType->value,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
