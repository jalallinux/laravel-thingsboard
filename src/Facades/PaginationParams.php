<?php

namespace JalalLinuX\Tntity\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method PaginationParams page(int $page)
 * @method PaginationParams perPage(int $perPage)
 * @method PaginationParams setSortProperty(string $sortProperty)
 * @method PaginationParams setSortOrder(string $sortOrder)
 */
class PaginationParams extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'thingsboard.PaginationParams';
    }
}
