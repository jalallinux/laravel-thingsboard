<?php

namespace JalalLinuX\Tntity\Facade;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Facade;

/**
 * @method static static make(array $attributes = [])
 * @method JsonResource toResource(string $class)
 * @method array getAttributes()
 * @method mixed get($key = null, $default = null)
 */
abstract class EntityFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        $entityInstanceName = last(explode('\\', static::class));

        return config('thingsboard.container.namespace') . '.'
            . config('thingsboard.container.prefix.entity') . '.'
            . $entityInstanceName;
    }
}
