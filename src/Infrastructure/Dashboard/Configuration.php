<?php

namespace JalalLinuX\Thingsboard\Infrastructure\Dashboard;

use JalalLinuX\Thingsboard\Infrastructure\HasCustomCasts;
use Jenssegers\Model\Model;

/**
 * @property string $description
 * @property array $widgets
 * @property array $states
 * @property array $entityAliases
 * @property array $filters
 * @property array $timewindow
 * @property array $settings
 */
class Configuration extends Model
{
    use HasCustomCasts;

    protected $fillable = [
        'description',
        'widgets',
        'states',
        'entityAliases',
        'filters',
        'timewindow',
        'settings',
    ];

    protected $casts = [
        'widgets' => 'array',
        'states' => 'array',
        'entityAliases' => 'array',
        'filters' => 'array',
        'timewindow' => 'array',
        'settings' => 'array',
    ];
}
