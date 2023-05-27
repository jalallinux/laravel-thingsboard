<?php

namespace JalalLinuX\Thingsboard\infrastructure;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumThingsboardEntityType;

class Id
{
    public string $id;

    public EnumThingsboardEntityType $entityType;

    public function __construct(string $id, string $entityType)
    {
        throw_if(
            ! Str::isUuid($id),
            new \Exception('Id must be a valid uuid.')
        );
        $this->id = $id;
        $this->entityType = EnumThingsboardEntityType::from($entityType);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'entityType' => $this->entityType->value,
        ];
    }
}
