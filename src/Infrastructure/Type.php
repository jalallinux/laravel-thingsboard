<?php

namespace JalalLinuX\Thingsboard\Infrastructure;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;

class Type
{
    private Id $id;

    private EnumEntityType $entityType;

    private string $type;

    public function __construct(Id $id, EnumEntityType $entityType, string $type)
    {
        $this->id = $id;
        $this->entityType = EnumEntityType::from($entityType);
        $this->type = $type;
    }

    public static function make(array $type): self
    {
        $id = last(Arr::except($type, ['entityType', 'type']));

        return new self(new Id($id['id'], $id['entityType']), EnumEntityType::from($type['entityType']), $type['type']);
    }

    public function entityType(): string
    {
        return $this->entityType;
    }

    public function setEntityType(EnumEntityType $entityType): self
    {
        return tap($this, fn () => $this->entityType = $entityType);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        return tap($this, fn () => $this->type = $type);
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function setId(Id $id): self
    {
        return tap($this, fn () => $this->id = $id);
    }
}
