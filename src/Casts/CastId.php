<?php

namespace JalalLinuX\Thingsboard\Casts;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\infrastructure\Id;
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

        throw_if(! is_array($value) || ! array_key_exists('id', $value) || ! array_key_exists('entityType', $value), new \Exception('Attribute must have id, entityType key in a array.'));
        throw_if(! Str::isUuid($value['id']), new \Exception('Id must be a valid uuid.'));

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
