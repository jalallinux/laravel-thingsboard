<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastBase64Image;
use JalalLinuX\Thingsboard\Casts\CastDescriptor;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumDefaultWidgetTypeDescriptor;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\WidgetType\Descriptor;
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
     * Get the Widget Type based on the provided parameters.
     * Widget Type represents the template for widget creation.
     * Widget Type and Widget are similar to class and object in OOP theory.
     *
     * @param  EnumDefaultWidgetTypeDescriptor  $defaultWidgetTypesDescriptor
     * @return Descriptor
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getDefaultWidgetTypeDescriptor(EnumDefaultWidgetTypeDescriptor $defaultWidgetTypesDescriptor): Descriptor
    {
        $defaultWidgetTypesDescriptors = collect(config('thingsboard.default_widget_type_descriptors'));
        $descriptorParams = $defaultWidgetTypesDescriptors->firstWhere('enum', $defaultWidgetTypesDescriptor);

        return $this->getWidgetType($descriptorParams['bundleAlias'], $descriptorParams['alias'], $descriptorParams['isSystem'])->descriptor;
    }

    /**
     * Get the Widget Type Details based on the provided Widget Type ID.
     * Widget Type Details extend Widget Type and add image and description properties.
     * Those properties are useful to edit the Widget Type, but they are not required for Dashboard rendering.
     *
     * @param  string|null  $id
     * @return WidgetType
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getWidgetTypeById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'widgetTypeId']);

        $widgetType = $this->api()->get("widgetType/{$id}")->json();

        return $this->fill($widgetType);
    }

    /**
     * Returns an array of Widget Type objects that belong to specified Widget Bundle.
     * Widget Type represents the template for widget creation.
     * Widget Type and Widget are similar to class and object in OOP theory.
     *
     * @param  string|null  $bundleAlias
     * @param  bool  $isSystem
     * @return array
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getBundleWidgetTypes(string $bundleAlias = null, bool $isSystem = true): array
    {
        $bundleAlias = $bundleAlias ?? $this->forceAttribute('bundleAlias');

        $widgetTypes = $this->api()->get('widgetTypes', ['isSystem' => $isSystem, 'bundleAlias' => $bundleAlias])->json();

        return array_map(fn ($widgetType) => new self($widgetType), $widgetTypes);
    }

    /**
     * Returns an array of Widget Type Details objects that belong to specified Widget Bundle.
     * Widget Type Details extend Widget Type and add image and description properties.
     * Those properties are useful to edit the Widget Type, but they are not required for Dashboard rendering.
     *
     * @param  string|null  $bundleAlias
     * @param  bool  $isSystem
     * @return array
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getBundleWidgetTypesDetails(string $bundleAlias = null, bool $isSystem = true): array
    {
        $bundleAlias = $bundleAlias ?? $this->forceAttribute('bundleAlias');

        $widgetTypes = $this->api()->get('widgetTypesDetails', ['isSystem' => $isSystem, 'bundleAlias' => $bundleAlias])->json();

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
     * @param  string|null  $name
     * @param  string|null  $bundleAlias
     * @param  Descriptor|null  $descriptor
     * @return self
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
//    public function saveWidgetType(string $name = null, string $bundleAlias = null, Descriptor $descriptor = null): static
//    {
//        $payload = array_merge($this->attributes, [
//            'name' => $name ?? $this->forceAttribute('name'),
//            'bundleAlias' => $bundleAlias ?? $this->forceAttribute('bundleAlias'),
//            'descriptor' => ($descriptor ?? $this->forceAttribute('descriptor'))->toArray(),
//        ]);
//
//        $widgetType = $this->api()->post('widgetType', $payload)->json();
//
//        return $this->fill($widgetType);
//    }
}
