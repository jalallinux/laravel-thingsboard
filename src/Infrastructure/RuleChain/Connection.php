<?php

namespace JalalLinuX\Thingsboard\Infrastructure\RuleChain;

use Jenssegers\Model\Model;
use Vkovic\LaravelCustomCasts\HasCustomCasts;

/**
 * @property int $fromIndex
 * @property int $toIndex
 * @property string $type
 * */
class Connection extends Model
{
    use HasCustomCasts;

    protected $fillable = [
        'fromIndex',
        'toIndex',
        'type',
    ];

    protected $casts = [
        'fromIndex' => 'integer',
        'toIndex' => 'integer'
    ];
}
