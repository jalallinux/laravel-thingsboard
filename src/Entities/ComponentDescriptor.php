<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Enums\EnumComponentDescriptorClusteringMode;
use JalalLinuX\Thingsboard\Enums\EnumComponentDescriptorScope;
use JalalLinuX\Thingsboard\Enums\EnumComponentDescriptorType;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumRuleChainType;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $data
 * @property int $totalPages
 * @property int $totalElements
 * @property bool $hasNext
 * @property array $entityFields
 * @property array $entityFilter
 * @property array $keyFilters
 * @property array $latestValues
 * @property array $pageLink
 */
class ComponentDescriptor extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'type',
        'scope',
        'clusteringMode',
        'name',
        'clazz',
        'configurationDescriptor',
        'actions',
    ];

    protected $casts = [
        'id' => 'array',
        'createdTime' => 'timestamp',
        'type' => EnumComponentDescriptorType::class,
        'scope' => EnumComponentDescriptorScope::class,
        'clusteringMode' => EnumComponentDescriptorClusteringMode::class,
        'configurationDescriptor' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Gets the Component Descriptors using coma separated list of rule node types and optional rule chain type request parameters.
     * Each Component Descriptor represents configuration of specific rule node (e.g. 'Save Timeseries' or 'Send Email'.).
     * The Component Descriptors are used by the rule chain Web UI to build the configuration forms for the rule nodes.
     * The Component Descriptors are discovered at runtime by scanning the class path and searching for @RuleNode annotation.
     * Once discovered, the up to date list of descriptors is persisted to the database.
     *
     * @param  EnumComponentDescriptorType[]|null  $componentTypes
     * @param  EnumRuleChainType|null  $ruleChainType
     * @return self[]
     *
     * @author Sabiee
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function getComponentDescriptorsByTypes(array $componentTypes, EnumRuleChainType $ruleChainType = null): array
    {
        Thingsboard::validation(empty($componentTypes), 'required', ['attribute' => 'componentTypes']);

        foreach ($componentTypes as $type) {
            Thingsboard::validation(! ($type instanceof EnumComponentDescriptorType), 'instance_of', [
                'attribute' => $type,
                'instance' => EnumComponentDescriptorType::class,
            ]);
        }
        $queryParams = array_filter([
            'componentTypes' => implode(',', array_map(fn ($componentType) => $componentType->value, $componentTypes)),
            'ruleChainType' => @$ruleChainType->value,
        ]);
        $components = $this->api()->get('components', $queryParams)->json();

        return array_map(fn ($component) => new self($component), $components);
    }
}
