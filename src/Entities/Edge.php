<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEdgeSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $tenantId
 * @property Id $customerId
 * @property Id $rootRuleChainId
 * @property \DateTime $createdTime
 * @property string $name
 * @property string $type
 * @property string $label
 * @property string $routingKey
 * @property string $secret
 * @property string $customerTitle
 * @property bool $customerIsPublic
 */
class Edge extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'customerId',
        'rootRuleChainId',
        'name',
        'type',
        'label',
        'routingKey',
        'secret',
        'customerIsPublic',
        'customerTitle',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'rootRuleChainId' => CastId::class,
        'additionalInfo' => 'array',
        'createdTime' => 'timestamp',
        'customerIsPublic' => 'boolean',
    ];
    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::EDGE();
    }

//    /**
//     * Creates assignment of the edge to customer.
//     * Customer will be able to query edge afterward.
//     *
//     * @param string|null $id
//     * @param string|null $customerId
//     * @return Edge
//     *
//     * @author JalalLinuX
//     *
//     * @group TENANT_ADMIN
//     */
//    public function assignEdgeToCustomer(string $id = null, string $customerId = null): static
//    {
//        $id = $id ?? $this->forceAttribute('edgeId')->id;
//        $customerId = $customerId ?? $this->forceAttribute('customerId')->id;
//
//        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'edgeId']);
//        Thingsboard::validation(! Str::isUuid($customerId), 'uuid', ['attribute' => 'customerId']);
//
//        $edge = $this->api()->post("customer/{$customerId}/edge/{$id}")->json();
//
//        return $this->fill($edge);
//    }

    /**
     * Returns a page of edges info objects owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * Edge Info is an extension of the default Edge object that contains information about the assigned customer name.
     *
     * @param PaginationArguments $paginationArguments
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getTenantEdgeInfos(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumEdgeSortProperty::class);

        $response = $this->api()->get("tenant/edgeInfos", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of edges owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param PaginationArguments $paginationArguments
     * @param string|null $type
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getTenantEdges(PaginationArguments $paginationArguments, string $type = null): LengthAwarePaginator
    {
        $type = $type ?? $this->getAttribute('type');

        $paginationArguments->validateSortProperty(EnumEdgeSortProperty::class);

        $response = $this->api()->get("tenant/edgeInfos", $paginationArguments->queryParams(['type' => $type]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
