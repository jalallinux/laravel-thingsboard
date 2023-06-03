<?php

namespace JalalLinuX\Thingsboard\Casts;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Thingsboard;
use Vkovic\LaravelCustomCasts\CustomCastBase;

class CastId extends CustomCastBase
{
    public function setAttribute($value): ?array
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Id) {
            return $value->toArray();
        }

        Thingsboard::validation(
            ! is_array($value) || ! array_key_exists('id', $value) || ! array_key_exists('entityType', $value),
            'assoc_array_of', ['attribute' => 'attribute', 'struct' => "['' => 'uuid', 'entityType' => 'EnumEntityType']"]
        );

        Thingsboard::validation(! Str::isUuid($value['id']), 'uuid', ['attribute' => 'id']);

        return [
            'id' => $value['id'],
            'entityType' => EnumEntityType::from($value['entityType'])->value,
        ];
    }

    public function castAttribute($value): ?Id
    {
        return is_null($value) ? null : new Id($value['id'], $value['entityType']);
    }
}
