<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumAssetProfileQueue;
use JalalLinuX\Thingsboard\Enums\EnumAssetProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $tenantId
 * @property string $name
 * @property bool $default
 * @property Id $defaultDashboardId
 * @property Id $defaultRuleChainId
 * @property EnumAssetProfileQueue $defaultQueueName
 * @property string $description
 * @property string $image
 * @property Id $defaultEdgeRuleChainId
 */
class AssetProfile extends Tntity
{
    protected $fillable = [
        'id',
        'tenantId',
        'name',
        'default',
        'defaultDashboardId',
        'defaultRuleChainId',
        'defaultQueueName',
        'description',
        'image',
        'defaultEdgeRuleChainId',
    ];

    protected $casts = [
        'default' => 'boolean',
        'defaultDashboardId' => CastId::class,
        'defaultRuleChainId' => CastId::class,
        'defaultEdgeRuleChainId' => CastId::class,
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'assetProfileId' => CastId::class,
        'defaultQueueName' => EnumAssetProfileQueue::class,
        'additionalInfo' => 'array',
        'createdTime' => 'timestamp',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::ASSET_PROFILE();
    }

    /**
     * Returns a page of asset profile info objects owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * Asset Profile Info is a lightweight object that includes main information about Asset Profile.
     *
     * @param  PaginationArguments  $paginationArguments
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getAssetProfileInfos(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumAssetProfileSortProperty::class);

        $response = $this->api()->get('assetProfileInfos', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Fetch the Asset Profile object based on the provided Asset Profile ID.
     * The server checks that the asset profile is owned by the same tenant.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getAssetProfileById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetProfileId']);

        $device = $this->api()->get("assetProfile/{$id}")->json();

        return $this->fill($device);
    }

    /**
     * Create or update the Asset Profile.
     * When creating asset profile, platform generates asset profile id as time-based UUID.
     * The newly created asset profile id will be present in the response.
     * Specify existing asset profile id to update the asset profile.
     * Referencing non-existing asset profile Id will cause 'Not Found' error.
     * Asset profile name is unique in the scope of tenant.
     * Only one 'default' asset profile may exist in scope of tenant.
     * Remove 'id', 'tenantId' from the request body example (below) to create new Asset Profile entity.
     *
     * @param  string|null  $name
     * @return self
     *
     * @author JalalLinuX
     */
    public function saveAssetProfile(string $name = null): static
    {
        $payload = array_merge($this->attributesToArray(), [
            'name' => $name ?? $this->forceAttribute('name'),
        ]);

        $assetProfile = $this->api()->post('assetProfile', $payload)->json();

        return $this->fill($assetProfile);
    }

    /**
     * Deletes the asset profile.
     * Referencing non-existing asset profile ID will cause an error.
     * Can't delete the asset profile if it is referenced by existing assets.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author JalalLinuX
     */
    public function deleteAssetProfile(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetProfileId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("assetProfile/{$id}")->successful();
    }

    /**
     * Returns a page of asset profile objects owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getAssetProfiles(PaginationArguments $paginationArguments): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumAssetProfileSortProperty::class);

        $response = $this->api()->get('assetProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Fetch the Asset Profile Info object based on the provided Asset Profile ID.
     * Asset Profile Info is a lightweight object that includes main information about Asset Profile.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getAssetProfileInfoById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetProfileId']);

        $device = $this->api()->get("assetProfileInfo/{$id}")->json();

        return $this->fill($device);
    }

    /**
     * Fetch the Default Asset Profile Info object.
     * Asset Profile Info is a lightweight object that includes main information about Asset Profile.
     *
     * @param bool $full
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getDefaultAssetProfileInfo(bool $full = false): static
    {
        $assetProfile = $this->api()->get('assetProfileInfo/default')->json();

        if ($full) {
            return $this->getAssetProfileById($assetProfile['id']['id']);
        }

        return $this->fill($assetProfile);
    }

    /**
     * Marks asset profile as default within a tenant scope.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function setDefaultAssetProfile(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetProfileId']);

        $device = $this->api()->post("assetProfile/{$id}/default")->json();

        return $this->fill($device);
    }
}
