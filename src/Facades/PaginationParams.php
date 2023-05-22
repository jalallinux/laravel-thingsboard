<?php

namespace JalalLinuX\Thingsboard\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static page(int $page)
 * @method static perPage(int $perPage)
 * @method static setSortProperty(string $sortProperty)
 * @method static setSortOrder(string $sortOrder)
 */
class PaginationParams extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return config('thingsboard.container.namespace') . ".PaginationParams";
    }
}
