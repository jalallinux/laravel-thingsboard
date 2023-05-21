<?php

namespace JalalLinuX\Tntity\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static static make(array $attributes = [])
 */
abstract class EntityFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        $entityInstanceName = last(explode('\\', static::class));

        return config('thingsboard.container.namespace')
            .'.'.config('thingsboard.container.prefix.entity')
            .'.'.$entityInstanceName;
    }
}
