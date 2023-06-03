<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastBase64Image;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property \DateTime $createdTime
 * @property Id $tenantId
 * @property string $bundleAlias
 * @property string $alias
 * @property string $name
 * @property array $descriptor
 * @property Base64Image $image
 * @property string $description
 */
class WidgetType extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'bundleAlias',
        'alias',
        'name',
        'descriptor',
        'image',
        'description',
    ];

    protected $casts = [
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'tenantId' => CastId::class,
        'descriptor' => 'array',
        'image' => CastBase64Image::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::WIDGET_TYPE();
    }

    /**
     * Get the Widget Type based on the provided parameters.
     * Widget Type represents the template for widget creation.
     * Widget Type and Widget are similar to class and object in OOP theory.
     * @param bool $isSystem
     * @param string|null $bundleAlias
     * @param string|null $alias
     * @return self
     * @author JalalLinuX
     * @group *
     */
    public function getWidgetType(string $bundleAlias = null, string $alias = null, bool $isSystem = true): static
    {
        [$bundleAlias, $alias] = [
            $bundleAlias ?? $this->forceAttribute('bundleAlias'), $alias ?? $this->forceAttribute('alias')
        ];

        $widgetType = $this->api()->get("widgetType", [
            'isSystem' => $isSystem, 'bundleAlias' => $bundleAlias, 'alias' => $alias,
        ])->json();

        return tap($this, fn() => $this->fill($widgetType));
    }

    /**
     * Get the Widget Type Info objects based on the provided parameters.
     * Widget Type Info is a lightweight object that represents Widget Type but does not contain the heavyweight widget descriptor JSON
     * @param bool $isSystem
     * @param string|null $bundleAlias
     * @return self[]
     * @author JalalLinuX
     * @group *
     */
    public function getBundleWidgetTypesInfos(string $bundleAlias = null, bool $isSystem = true): array
    {
        $bundleAlias = $bundleAlias ?? $this->forceAttribute('bundleAlias');

        $widgetTypes = $this->api()->get("widgetTypesInfos", ['isSystem' => $isSystem, 'bundleAlias' => $bundleAlias,])->json();

        return array_map(fn ($widgetType) => new self($widgetType), $widgetTypes);
    }
}
