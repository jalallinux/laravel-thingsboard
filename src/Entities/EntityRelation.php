<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $filters
 * @property array $parameters
 * @property Id $from
 * @property Id $to
 * @property string $type
 * @property string $typeGroup
 * @property array $additionalInfo
 * @property array $fromName
 * @property array $toName
 */
class EntityRelation extends Tntity
{
    protected $fillable = [
        'filters',
        'parameters',
        'from',
        'to',
        'type',
        'typeGroup',
        'additionalInfo',
        'fromName',
        'toName',
    ];

    protected $casts = [
        'filters' => 'array',
        'parameters' => 'array',
        'from' => CastId::class,
        'to' => CastId::class,
        'additionalInfo' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Returns all entity infos that are related to the specific entity.
     * The entity id, relation type, entity types, depth of the search, and other query parameters defined using complex
     * 'EntityRelationsQuery' object. See 'Model' tab of the Parameters for more info.
     * Relation Info is an extension of the default Relation object that contains information about
     * the 'from' and 'to' entity names.
     *
     * @param  array|null  $filters
     * @param  array|null  $parameters
     * @return array
     *
     * @author Sabiee
     */
    public function findInfoByQuery(array $filters = null, array $parameters = null): array
    {
        Thingsboard::validation(empty($filters) && empty($this->getAttribute('filters')), 'required', ['attribute' => 'filters']);
        Thingsboard::validation(empty($parameters) && empty($this->getAttribute('parameters')), 'required', ['attribute' => 'parameters']);

        $payload = [
            'filters' => $filters ?? $this->forceAttribute('filters'),
            'parameters' => $parameters ?? $this->forceAttribute('parameters'),
        ];

        $relations = $this->api()->post('relations/info', $payload)->json();

        return array_map(fn ($relation) => new EntityRelation($relation), $relations);
    }

    /**
     * Creates or updates a relation between two entities in the platform. Relations unique key is a
     * combination of from/to entity id and relation type group and relation type.
     *
     * If the user has the authority of 'System Administrator', the server checks that 'from' and 'to' entities are owned by the sysadmin.
     * If the user has the authority of 'Tenant Administrator', the server checks that 'from' and 'to' entities are owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the 'from' and 'to' entities are assigned to the same customer.
     *
     * @param  Id|null  $from
     * @param  Id|null  $to
     * @param  string|null  $type
     * @param  string|null  $typeGroup
     * @param  array|null  $additionalInfo
     * @return bool
     *
     * @author  Sabiee
     */
    public function saveRelation(Id $from = null, Id $to = null, string $type = null, string $typeGroup = null, array $additionalInfo = null): bool
    {
        $payload = [
            'from' => [
                'id' => @$from->id ?? $this->forceAttribute('from')->id,
                'entityType' => @$from->entityType ?? $this->forceAttribute('from')->entityType,
            ],
            'to' => [
                'id' => @$to->id ?? $this->forceAttribute('to')->id,
                'entityType' => @$to->entityType ?? $this->forceAttribute('to')->entityType,
            ],
            'type' => @$type ?? $this->forceAttribute('type'),
            'typeGroup' => @$typeGroup ?? $this->getAttribute('typeGroup'),
            'additionalInfo' => $additionalInfo ?? $this->getAttribute('additionalInfo'),
        ];

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->post('relation', $payload)->successful();
    }

    /**
     * Deletes a relation between two entities in the platform.
     * If the user has the authority of 'System Administrator', the server checks that 'from' and 'to' entities are owned by the sysadmin.
     * If the user has the authority of 'Tenant Administrator', the server checks that 'from' and 'to' entities are owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the 'from' and 'to' entities are assigned to the same customer.
     *
     * @author Sabiee
     */
    public function deleteRelation(Id $from = null, string $relationType = null, Id $to = null, string $relationTypeGroup = null): bool
    {
        $queryParams = [
            'fromId' => @$from->id ?? $this->forceAttribute('from')->id,
            'fromType' => @$from->entityType->value ?? $this->forceAttribute('from')->entityType->value,
            'relationType' => @$relationType ?? $this->forceAttribute('relationType'),
            'relationTypeGroup' => @$relationTypeGroup ?? $this->getAttribute('relationTypeGroup'),
            'toId' => @$to->id ?? $this->forceAttribute('to')->id,
            'toType' => @$to->entityType->value ?? $this->forceAttribute('to')->entityType->value,
        ];

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->bodyFormat('query')
            ->delete('relation', $queryParams)->successful();
    }

    /**
     * Returns relation object between two specified entities if present. Otherwise throws exception.
     * If the user has the authority of 'System Administrator', the server checks that 'from' and 'to' entities are
     * owned by the sysadmin. If the user has the authority of 'Tenant Administrator', the server checks that 'from' and
     * 'to' entities are owned by the same tenant. If the user has the authority of 'Customer User',
     * the server checks that the 'from' and 'to' entities are assigned to the same customer.
     *
     * @param  Id|null  $from
     * @param  string|null  $relationType
     * @param  Id|null  $to
     * @param  string|null  $relationTypeGroup
     * @return EntityRelation
     *
     * @author Sabiee
     */
    public function getRelation(Id $from = null, string $relationType = null, Id $to = null, string $relationTypeGroup = null): static
    {
        $queryParams = [
            'fromId' => @$from->id ?? $this->forceAttribute('from')->id,
            'fromType' => @$from->entityType->value ?? $this->forceAttribute('from')->entityType->value,
            'relationType' => @$relationType ?? $this->forceAttribute('relationType'),
            'relationTypeGroup' => @$relationTypeGroup ?? $this->getAttribute('relationTypeGroup'),
            'toId' => @$to->id ?? $this->forceAttribute('to')->id,
            'toType' => @$to->entityType->value ?? $this->forceAttribute('to')->entityType->value,
        ];

        $relation = $this->api()->get('relation', $queryParams)->json();

        return $this->fill($relation);
    }

    /**
     * Deletes all the relation (both 'from' and 'to' direction) for the specified entity.
     * If the user has the authority of 'System Administrator', the server checks that the entity is owned by the sysadmin.
     * If the user has the authority of 'Tenant Administrator', the server checks that the entity is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the entity is assigned to the same customer.
     *
     * @param  Id|null  $entity
     * @return bool
     *
     * @author Sabiee
     */
    public function deleteRelations(Id $entity = null): bool
    {
        $queryParams = [
            'entityId' => @$entity->id ?? @$this->getAttribute('from')->id ?? @$this->getAttribute('to')->id,
            'entityType' => @$entity->entityType->value ?? @$this->getAttribute('from')->entityType->value ?? @$this->getAttribute('to')->entityType->value,
        ];

        Thingsboard::validation(is_null($queryParams['entityId']), 'required', ['attribute' => 'entityId']);
        Thingsboard::validation(is_null($queryParams['entityType']), 'required', ['attribute' => 'entityType']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->bodyFormat('query')
            ->delete('relations', $queryParams)->successful();
    }

    /**
     * Returns list of relation info objects for the specified entity by the 'from' direction.
     * If the user has the authority of 'System Administrator', the server checks that the entity is owned by the sysadmin.
     * If the user has the authority of 'Tenant Administrator', the server checks that the entity is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the entity is assigned to the same customer.
     * Relation Info is an extension of the default Relation object that contains information about the 'from' and 'to' entity names.
     *
     * @param  Id|null  $fromType
     * @param  string|null  $relationTypeGroup
     * @return array
     *
     * @author Sabiee
     */
    public function findInfoByFrom(Id $fromType = null, string $relationTypeGroup = null): array
    {
        $queryParams = [
            'fromId' => @$fromType->id ?? @$this->forceAttribute('from')->id,
            'fromType' => @$fromType->entityType->value ?? @$this->forceAttribute('from')->entityType->value,
            'relationTypeGroup' => @$relationTypeGroup ?? @$this->getAttribute('relationTypeGroup'),
        ];

        $relations = $this->api()->get('relations/info', $queryParams)->json();

        return array_map(fn ($relation) => new EntityRelation($relation), $relations);
    }

    /**
     * Returns list of relation info objects for the specified entity by the 'to' direction.
     * If the user has the authority of 'System Administrator', the server checks that the entity is owned by the sysadmin.
     * If the user has the authority of 'Tenant Administrator', the server checks that the entity is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the entity is assigned to the same customer.
     * Relation Info is an extension of the default Relation object that contains information about the 'from' and 'to' entity names.
     *
     * @param  Id|null  $to
     * @param  string|null  $relationTypeGroup
     * @return array
     *
     * @author Sabiee
     */
    public function findInfoByTo(Id $to = null, string $relationTypeGroup = null): array
    {
        $queryParams = [
            'toId' => @$to->id ?? @$this->forceAttribute('to')->id,
            'toType' => @$to->entityType->value ?? @$this->forceAttribute('to')->entityType->value,
            'relationTypeGroup' => @$relationTypeGroup ?? @$this->getAttribute('relationTypeGroup'),
        ];

        $relations = $this->api()->get('relations/info', $queryParams)->json();

        return array_map(fn ($relation) => new EntityRelation($relation), $relations);
    }
}
