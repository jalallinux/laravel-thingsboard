<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastBase64Image;
use JalalLinuX\Thingsboard\Casts\CastDescriptor;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Descriptor;
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
 * @property Descriptor $descriptor
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
        'descriptor' => CastDescriptor::class,
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
     *
     * @param  bool  $isSystem
     * @param  string|null  $bundleAlias
     * @param  string|null  $alias
     * @return self
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function getWidgetType(string $bundleAlias = null, string $alias = null, bool $isSystem = true): static
    {
        [$bundleAlias, $alias] = [
            $bundleAlias ?? $this->forceAttribute('bundleAlias'), $alias ?? $this->forceAttribute('alias'),
        ];

        $widgetType = $this->api()->get('widgetType', [
            'isSystem' => $isSystem, 'bundleAlias' => $bundleAlias, 'alias' => $alias,
        ])->json();

        return $this->fill($widgetType);
    }

    /**
     * Get the Widget Type Info objects based on the provided parameters.
     * Widget Type Info is a lightweight object that represents Widget Type but does not contain the heavyweight widget descriptor JSON
     *
     * @param  bool  $isSystem
     * @param  string|null  $bundleAlias
     * @return self[]
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function getBundleWidgetTypesInfos(string $bundleAlias = null, bool $isSystem = true): array
    {
        $bundleAlias = $bundleAlias ?? $this->forceAttribute('bundleAlias');

        $widgetTypes = $this->api()->get('widgetTypesInfos', ['isSystem' => $isSystem, 'bundleAlias' => $bundleAlias])->json();

        return array_map(fn ($widgetType) => new self($widgetType), $widgetTypes);
    }

    /**
     * Create or update the Widget Type. Widget Type represents the template for widget creation.
     * Widget Type and Widget are similar to class and object in OOP theory.
     * When creating the Widget Type, platform generates Widget Type ID as time-based UUID.
     * The newly created Widget Type ID will be present in the response. Specify existing Widget Type id to update the Widget Type.
     * Referencing non-existing Widget Type ID will cause 'Not Found' error.
     * Widget Type alias is unique in the scope of Widget Bundle.
     * Special Tenant ID '13814000-1dd2-11b2-8080-808080808080' is automatically used if the create request is sent by user with 'SYS_ADMIN' authority.
     * Remove 'id', 'tenantId' rom the request body example (below) to create new Widget Type entity.
     *
     * @param string|null $name
     * @param string|null $bundleAlias
     * @param Descriptor|null $descriptor
     * @return self
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function saveWidgetType(string $name = null, string $bundleAlias = null, Descriptor $descriptor = null): static
    {
        $payload = array_merge($this->attributes, [
            'name' => $name ?? $this->forceAttribute('name'),
            'bundleAlias' => $bundleAlias ?? $this->forceAttribute('bundleAlias'),
            'descriptor' => ($descriptor ?? $this->forceAttribute('descriptor'))->toArray(),
        ]);

        $widgetType = $this->api()->post("widgetType", $payload)->json();

        return $this->fill($widgetType);
    }

    /**
     * Get the Widget Type based on the provided parameters.
     * Widget Type represents the template for widget creation.
     * Widget Type and Widget are similar to class and object in OOP theory.
     *
     * @param string $defaultWidgetTypesDescriptor
     * @return Descriptor
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getDefaultWidgetTypeDescriptor(string $defaultWidgetTypesDescriptor): Descriptor
    {
        Thingsboard::validation(is_null($params = config("thingsboard.default_widget_type_descriptors.{$defaultWidgetTypesDescriptor}")), 'in', [
            'attribute' => 'defaultWidgetTypes', 'values' => implode(', ', array_keys(config('thingsboard.default_widget_type_descriptors')))
        ]);

        return $this->getWidgetType($params['bundleAlias'], $params['alias'], $params['isSystem'])->descriptor;
    }
}
