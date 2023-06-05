<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastBase64Image;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumDashboardSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property \DateTime $createdTime
 * @property Id $tenantId
 * @property string $name
 * @property string $title
 * @property array $assignedCustomers
 * @property bool $mobileHide
 * @property int $mobileOrder
 * @property Base64Image $image
 * @property array $configuration
 */
class Dashboard extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'name',
        'title',
        'assignedCustomers',
        'mobileHide',
        'mobileOrder',
        'image',
        'configuration',
    ];

    protected $casts = [
        'id' => CastId::class,
        'createdTime' => 'timestamp',
        'tenantId' => CastId::class,
        'assignedCustomers' => 'arrayy',
        'mobileHide' => 'bool',
        'mobileOrder' => 'int',
        'image' => CastBase64Image::class,
        'configuration' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::DASHBOARD();
    }

    /**
     * Returns a page of dashboard info objects owned by the tenant of a current user.
     * The Dashboard Info object contains lightweight information about the dashboard (e.g. title, image, assigned customers),
     * but does not contain the heavyweight configuration JSON.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  bool|null  $mobileHide
     * @return PaginatedResponse
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getDashboards(PaginationArguments $paginationArguments, bool $mobileHide = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumDashboardSortProperty::class);

        $response = $this->api()->get('tenant/dashboards', $paginationArguments->queryParams([
            'mobile' => $mobileHide,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of dashboard info objects owned by tenant.
     * The Dashboard Info object contains lightweight information about the dashboard (e.g. title, image, assigned customers),
     * but does not contain the heavyweight configuration JSON.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $tenantId
     * @return PaginatedResponse
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantDashboards(PaginationArguments $paginationArguments, string $tenantId = null): PaginatedResponse
    {
        $tenantId = $tenantId ?? $this->forceAttribute('tenantId')->id;
        $paginationArguments->validateSortProperty(EnumDashboardSortProperty::class);

        $response = $this->api()->get("tenant/{$tenantId}/dashboards", $paginationArguments->queryParams());
        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get the dashboard based on 'dashboardId' parameter.
     * The Dashboard object is a heavyweight object that contains information about the dashboard (e.g. title, image, assigned customers)
     * and also configuration JSON (e.g. layouts, widgets, entity aliases).
     * @param string|null $id
     * @return $this
     * @author JalalLinuX
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDashboardById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        $dashboard = $this->api()->get("dashboard/{$id}")->json();

        return $this->fill($dashboard);
    }
}
