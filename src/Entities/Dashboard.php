<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastBase64Image;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Casts\Dashboard\CastConfiguration;
use JalalLinuX\Thingsboard\Enums\EnumDashboardSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Dashboard\Configuration;
use JalalLinuX\Thingsboard\Infrastructure\Id;
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
 * @property Configuration $configuration
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
        'assignedCustomers' => 'array',
        'mobileHide' => 'bool',
        'mobileOrder' => 'int',
        'image' => CastBase64Image::class,
        'configuration' => CastConfiguration::class,
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
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getDashboards(PaginationArguments $paginationArguments, bool $mobileHide = null): LengthAwarePaginator
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
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN
     */
    public function getTenantDashboards(PaginationArguments $paginationArguments, string $tenantId = null): LengthAwarePaginator
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
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDashboardById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        $dashboard = $this->api()->get("dashboard/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Get the dashboard based on 'dashboardId' parameter.
     * The Dashboard object is a heavyweight object that contains information about the dashboard (e.g. title, image, assigned customers)
     * and also configuration JSON (e.g. layouts, widgets, entity aliases).
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDashboardInfoById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        $dashboard = $this->api()->get("dashboard/info/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Assign the Dashboard to specified Customer or do nothing if the Dashboard is already assigned to that Customer.
     *
     * @param  string  $customerId
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function assignDashboardToCustomer(string $customerId, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);
        Thingsboard::validation(! Str::isUuid($customerId), 'uuid', ['attribute' => 'customerId']);

        $dashboard = $this->api()->post("customer/{$customerId}/dashboard/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Unassign the Dashboard from specified Customer or do nothing if the Dashboard is already assigned to that Customer.
     *
     * @param  string  $customerId
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function unassignDashboardFromCustomer(string $customerId, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);
        Thingsboard::validation(! Str::isUuid($customerId), 'uuid', ['attribute' => 'customerId']);

        $dashboard = $this->api()->delete("customer/{$customerId}/dashboard/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Assigns the dashboard to a special, auto-generated 'Public' Customer.
     * Once assigned, unauthenticated users may browse the dashboard.
     * This method is useful if you like to embed the dashboard on public web pages to be available for users that are not logged in.
     * Be aware that making the dashboard public does not mean that it automatically makes all devices and assets you use in the dashboard to be public.
     * Use assign Asset to Public Customer and assign Device to Public Customer for this purpose.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function assignDashboardToPublicCustomer(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        $dashboard = $this->api()->post("customer/public/dashboard/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Unassigns the dashboard from a special, auto-generated 'Public' Customer.
     * Once unassigned, unauthenticated users may no longer browse the dashboard.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function unassignDashboardFromPublicCustomer(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        $dashboard = $this->api()->delete("customer/public/dashboard/{$id}")->json();

        return $this->fill($dashboard);
    }

    /**
     * Get the server time (milliseconds since January 1, 1970, UTC).
     * Used to adjust view of the dashboards according to the difference between browser and server time.
     *
     * @return Carbon
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function getServerTime(): Carbon
    {
        return Carbon::createFromTimestampMs(
            $this->api()->get('dashboard/serverTime')->body()
        );
    }

    /**
     * Returns a page of dashboard info objects owned by the specified customer.
     * The Dashboard Info object contains lightweight information about the dashboard (e.g. title, image, assigned customers) but does not contain the heavyweight configuration JSON.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  string  $customerId
     * @param  PaginationArguments  $paginationArguments
     * @param  bool|null  $mobileHide
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getCustomerDashboards(string $customerId, PaginationArguments $paginationArguments, bool $mobileHide = null): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumDashboardSortProperty::class);

        $response = $this->api()->get("customer/{$customerId}/dashboards", $paginationArguments->queryParams([
            'mobile' => $mobileHide,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Create or update the Dashboard.
     * When creating dashboard, platform generates Dashboard ID as time-based UUID.
     * The newly created Dashboard id will be present in the response.
     * Specify existing Dashboard id to update the dashboard.
     * Referencing non-existing dashboard ID will cause 'Not Found' error.
     * Remove 'id', 'tenantId' and optionally 'customerId' from the request body example (below) to create new Dashboard entity.
     *
     * @param  string|null  $title
     * @param  array|null  $configuration
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function saveDashboard(string $title = null, array $configuration = null): static
    {
        $payload = array_merge($this->attributes, [
            'title' => $title ?? $this->forceAttribute('title'),
            'configuration' => $configuration ?? $this->forceAttribute('configuration'),
        ]);

        $dashboard = $this->api()->post('dashboard', $payload)->json();

        return $this->fill($dashboard);
    }

    /**
     * Delete the Dashboard.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function deleteDashboard(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("dashboard/{$id}")->successful();
    }

    /**
     * @param  array  $customerIds
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function updateDashboardCustomers(array $customerIds, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'dashboardId']);

        foreach ($customerIds as $customerId) {
            Thingsboard::validation(! Str::isUuid($customerId), 'uuid', ['attribute' => 'customerId']);
        }

        $dashboard = $this->api()->post("dashboard/{$id}/customers", $customerIds)->json();

        return $this->fill($dashboard);
    }
}
