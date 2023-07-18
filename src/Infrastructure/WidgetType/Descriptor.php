<?php

namespace JalalLinuX\Thingsboard\Infrastructure\WidgetType;

use JalalLinuX\Thingsboard\Casts\CastObjectJsonString;
use JalalLinuX\Thingsboard\Enums\EnumDefaultWidgetTypeDescriptor;
use JalalLinuX\Thingsboard\Infrastructure\HasCustomCasts;
use Jenssegers\Model\Model;

/**
 * @property EnumDefaultWidgetTypeDescriptor $type
 * @property float $sizeX
 * @property float $sizeY
 * @property array $resources
 * @property string $templateHtml
 * @property string $templateCss
 * @property string $controllerScript
 * @property array $settingsSchema
 * @property array $dataKeySettingsSchema
 * @property array $defaultConfig
 */
class Descriptor extends Model
{
    use HasCustomCasts;

    protected $fillable = [
        'type',
        'sizeX',
        'sizeY',
        'resources',
        'templateHtml',
        'templateCss',
        'controllerScript',
        'settingsSchema',
        'dataKeySettingsSchema',
        'defaultConfig',
    ];

    protected $casts = [
        'type' => EnumDefaultWidgetTypeDescriptor::class,
        'sizeX' => 'float',
        'sizeY' => 'float',
        'resources' => 'array',
        'settingsSchema' => CastObjectJsonString::class,
        'dataKeySettingsSchema' => CastObjectJsonString::class,
        'defaultConfig' => CastObjectJsonString::class,
    ];
}
