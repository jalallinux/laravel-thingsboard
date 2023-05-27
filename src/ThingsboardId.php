<?php

namespace JalalLinuX\Thingsboard;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;

class ThingsboardId
{
    public string $id;

    public ThingsboardEntityType $entityType;

    public function __construct(string $id, string $entityType)
    {
        throw_if(
            !Str::isUuid($id),
            new \Exception("Id must be a valid uuid.")
        );
        $this->id = $id;
        $this->entityType = ThingsboardEntityType::from($entityType);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'entityType' => $this->entityType->value,
        ];
    }
}
