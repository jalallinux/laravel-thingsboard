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

    /**
     * Returns a page of edges info objects owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * Edge Info is an extension of the default Edge object that contains information about the assigned customer name.
     *
     * @param  PaginationArguments  $paginationArguments
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getTenantEdgeInfos(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumEdgeSortProperty::class);

        $response = $this->api()->get('tenant/edgeInfos', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of edges owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $type
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

        $response = $this->api()->get('tenant/edgeInfos', $paginationArguments->queryParams(['type' => $type]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get the Edge object based on the provided Edge Id.
     * If the user has the authority of 'Tenant Administrator', the server checks that the edge is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the edge is assigned to the same customer.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getEdgeById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'edgeId']);

        $dashboard = $this->api()->get("edge/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Deletes the edge.
     * Referencing non-existing edge Id will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function deleteEdge(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'edgeId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("edge/{$id}")->successful();
    }

    /**
     * Create or update the Edge.
     * When creating edge, platform generates Edge Id as time-based UUID.
     * The newly created edge id will be present in the response.
     * Specify existing Edge id to update the edge.
     * Referencing non-existing Edge Id will cause 'Not Found' error.
     *
     * Edge name is unique in the scope of tenant.
     * Use unique identifiers like MAC or IMEI for the edge names and non-unique 'label' field for user-friendly visualization purposes.
     * Remove 'id', 'tenantId' and optionally 'customerId' from the request body example (below) to create new Edge entity.
     *
     * @param  string|null  $name
     * @param  string|null  $type
     * @param  string|null  $secret
     * @param  string|null  $routingKey
     * @return Edge
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function saveEdge(string $name = null, string $type = null, string $secret = null, string $routingKey = null): static
    {
        $payload = array_merge($this->attributesToArray(), [
            'name' => $name,
            'type' => $type ?? $this->getAttribute('type') ?? 'default',
            'secret' => $secret ?? $this->getAttribute('secret') ?? uniqid(),
            'routingKey' => $routingKey ?? $this->getAttribute('routingKey') ?? Str::uuid()->toString(),
        ]);

        $edge = $this->api()->post('edge', $payload)->json();

        return $this->fill($edge);
    }

    /**
     * Creates assignment of the edge to customer.
     * Customer will be able to query edge afterward.
     *
     * @param  string|null  $customerId
     * @param  string|null  $id
     * @return Edge
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function assignEdgeToCustomer(string $customerId = null, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('edgeId')->id;
        $customerId = $customerId ?? $this->forceAttribute('customerId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'edgeId']);
        Thingsboard::validation(! Str::isUuid($customerId), 'uuid', ['attribute' => 'customerId']);

        $edge = $this->api()->post("customer/{$customerId}/edge/{$id}")->json();

        return $this->fill($edge);
    }

    /**
     * Clears assignment of the edge to customer.
     * Customer will not be able to query edge afterward.
     *
     * @param  string|null  $id
     * @return $this
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function unassignEdgeFromCustomer(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'edgeId']);

        $edge = $this->api()->delete("customer/edge/{$id}")->json();

        return $this->fill($edge);
    }

    /**
     * Edge will be available for non-authorized (not logged-in) users.
     * This is useful to create dashboards that you plan to share/embed on a publicly available website.
     * However, users that are logged-in and belong to different tenant will not be able to access the edge.
     *
     * @param  string|null  $id
     * @return Edge
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function assignEdgeToPublicCustomer(string $id = null)
    {
        $id = $id ?? $this->forceAttribute('edgeId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'edgeId']);

        $edge = $this->api()->post("customer/public/edge/{$id}")->json();

        return $this->fill($edge);
    }

    /**
     * Returns a page of edges objects assigned to customer.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  string  $customerId
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $type
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getCustomerEdges(string $customerId, PaginationArguments $paginationArguments, string $type = null): LengthAwarePaginator
    {
        Thingsboard::validation(! Str::isUuid($customerId), 'uuid', ['attribute' => 'customerId']);

        $paginationArguments->validateSortProperty(EnumEdgeSortProperty::class);

        $response = $this->api()->get("customer/{$customerId}/edges", $paginationArguments->queryParams([
            'type' => $type,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
