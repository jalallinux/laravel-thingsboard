<?php

namespace JalalLinuX\Tntity\Facades;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Facade;
use JalalLinuX\Tntity\Interfaces\ThingsboardUser;

/**
 * @method static $this make(array $attributes = [])
 * @method $this withUser(ThingsboardUser $user)
 * @method $this setAttribute($key, $value)
 * @method JsonResource toResource(string $class)
 * @method array getAttributes()
 * @method mixed get($key = null, $default = null)
 */
abstract class EntityFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        $entityInstanceName = last(explode('\\', static::class));

        return config('thingsboard.container.namespace').'.'
            .config('thingsboard.container.prefix.entity').'.'
            .$entityInstanceName;
    }
}
