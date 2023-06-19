<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastBase64Image;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumWidgetBundleSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Base64Image;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $tenantId
 * @property \DateTime $createdTime
 * @property string $name
 * @property string $alias
 * @property string $title
 * @property bool $systematic
 * @property Base64Image $image
 * @property string $description
 */
class WidgetBundle extends Tntity
{
    protected $fillable = [
        'id',
        'tenantId',
        'createdTime',
        'name',
        'alias',
        'title',
        'image',
        'description',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'image' => CastBase64Image::class,
        'createdTime' => 'timestamp',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::WIDGETS_BUNDLE();
    }

    public function getSystematicAttribute(): bool
    {
        return $this->tenantId->id == config('thingsboard.default.tenant_id');
    }

    /**
     * Returns a page of Widget Bundle objects available for current user.
     * Widget Bundle represents a group(bundle) of widgets.
     * Widgets are grouped into bundle by type or use case.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group
     */
    public function getWidgetsBundles(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumWidgetBundleSortProperty::class);
        $response = $this->api()->get('widgetsBundles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of Widget Bundle objects available for current user.
     * Widget Bundle represents a group(bundle) of widgets.
     * Widgets are grouped into bundle by type or use case.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @return self[]
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function getAllWidgetsBundles(): array
    {
        $widgetBundles = $this->api()->get('widgetsBundles')->json();

        return array_map(fn ($widgetBundle) => new self($widgetBundle), $widgetBundles);
    }

    /**
     * Get the Widget Bundle based on the provided Widget Bundle ID.
     * Widget Bundle represents a group(bundle) of widgets.
     * Widgets are grouped into bundle by type or use case.
     *
     * @param  string|null  $id
     * @return self
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group *
     */
    public function getWidgetsBundleById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'widgetBundleId']);

        $widgetBundle = $this->api()->get("widgetsBundle/{$id}")->json();

        return $this->fill($widgetBundle);
    }

    /**
     * Deletes the widget bundle.
     * Referencing non-existing Widget Bundle ID will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function deleteWidgetsBundle(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'widgetBundleId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("widgetsBundle/{$id}")->successful();
    }

    /**
     * Create or update the Widget Bundle. Widget Bundle represents a group(bundle) of widgets.
     * Widgets are grouped into bundle by type or use case.
     * When creating the bundle, platform generates Widget Bundle ID as time-based UUID.
     * The newly created Widget Bundle ID will be present in the response.
     * Specify existing Widget Bundle id to update the Widget Bundle.
     * Referencing non-existing Widget Bundle ID will cause 'Not Found' error.
     *
     * Widget Bundle alias is unique in the scope of tenant.
     * Special Tenant ID '13814000-1dd2-11b2-8080-808080808080' is automatically used if the create bundle request is sent by user with 'SYS_ADMIN' authority.
     * Remove 'id', 'tenantId' from the request body example (below) to create new Widgets Bundle entity.
     *
     * @param  string|null  $title
     * @return self
     *
     * @author JalalLinuX
     *
     * @group SYS_ADMIN | TENANT_ADMIN
     */
    public function saveWidgetsBundle(string $title = null): static
    {
        $payload = array_merge($this->attributesToArray(), [
            'title' => $title ?? $this->forceAttribute('title'),
        ]);

        $widgetBundle = $this->api()->post('widgetsBundle', $payload)->json();

        return $this->fill($widgetBundle);
    }
}
