<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEdgeEventAction;
use JalalLinuX\Thingsboard\Enums\EnumEdgeEventType;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property string $id
 * @property \DateTime $createdTime
 * @property Id $tenantId
 * @property Id $edgeId
 * @property EnumEdgeEventAction $action
 * @property EnumEdgeEventType $type
 * @property string $entityId
 * @property string $uid
 * @property string $body
 */
class EdgeEvent extends Tntity
{

    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'edgeId',
        'action',
        'type',
        'entityId',
        'uid',
        'body',
    ];

    protected $casts = [
        'id' => 'array',
        'tenantId' => CastId::class,
        'createdTime' => 'timestamp',
        'edgeId' => CastId::class,
        'action' => EnumEdgeEventAction::class,
        'type' => EnumEdgeEventType::class,
        'body' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::EDGE_EVENT();
    }

    /**
     * Returns a page of edge events for the requested edge.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param PaginationArguments $paginationArguments
     * @param string $edgeId
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getEdgeEvents(PaginationArguments $paginationArguments, string $edgeId): LengthAwarePaginator
    {
        Thingsboard::validation(!Str::isUuid($edgeId), 'uuid', ['attribute' => 'edgeId']);

        $response = $this->api()->get("edge/{$edgeId}/events", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
