<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Exceptions\Exception;

class Id
{
    public string $id;

    public EnumEntityType $entityType;

    public function __construct(string $id, string $entityType)
    {
        throw_if(
            ! Str::isUuid($id),
            new Exception(__('thingsboard::validation.uuid', ['attribute' => 'id']))
        );
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
}
