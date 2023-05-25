<?php

namespace JalalLinuX\Thingsboard\Interfaces;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;

class ThingsboardEntityId
{
    public string $id;

    public ThingsboardEntityType $entityType;

    public function __construct(string $id, ThingsboardEntityType $entityType)
    {
        throw_if(
            ! Str::isUuid($id),
            new \Exception("Entity id of type {$entityType} must be a valid uuid")
        );
        $this->id = $id;
        $this->entityType = $entityType;
    }

    public static function make(array $id): ThingsboardEntityId
    {
        return new self($id['id'], ThingsboardEntityType::from($id['entityType']));
    }
}
