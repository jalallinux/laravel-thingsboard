<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumAssetSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property string $name
 * @property string $type
 * @property string $label
 * @property Id $tenantId
 * @property Id $customerId
 * @property Id $assetProfileId
 * @property string $createdTime
 * @property array $additionalInfo
 * @property string $customerTitle
 * @property bool $customerIsPublic
 * @property string $assetProfileName
 */
class Asset extends Tntity
{
    protected $fillable = [
        'id',
        'name',
        'type',
        'label',
        'tenantId',
        'customerId',
        'assetProfileId',
        'createdTime',
        'additionalInfo',
        'customerTitle',
        'customerIsPublic',
        'assetProfileName',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'assetProfileId' => CastId::class,
        'additionalInfo' => 'array',
        'createdTime' => 'timestamp',
        'customerIsPublic' => 'boolean',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Creates or Updates the Asset.
     * When creating asset, platform generates Asset Id as time-based UUID.
     * The newly created Asset id will be present in the response.
     * Specify existing Asset id to update the asset.
     * Referencing non-existing Asset Id will cause 'Not Found' error. Remove 'id', 'tenantId' and optionally
     * 'customerId' from the request body example (below) to create new Asset entity.
     *
     *
     * @param  string|null  $assetProfileId
     * @return self
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function saveAsset(string $assetProfileId = null): static
    {
        $assetProfileId = $assetProfileId ?? $this->forceAttribute('assetProfileId')->id;

        $payload = array_merge($this->attributes, [
            'name' => $this->forceAttribute('name'),
            'assetProfileId' => new Id($assetProfileId, EnumEntityType::ASSET_PROFILE()),
        ]);

        return tap($this, fn () => $this->fill($this->api()->post('asset', $payload)->json()));
    }

    /**
     * Deletes the asset and all the relations (from and to the asset).
     * Referencing non-existing asset Id will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function deleteAsset(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("asset/{$id}")->successful();
    }

    /**
     * Returns a page of assets owned by tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getTenantAssets(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumAssetSortProperty::class);

        $response = $this->api()->get('tenant/assets', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Fetch the Asset object based on the provided Asset Id.
     * If the user has the authority of 'Tenant Administrator', the server checks that the asset is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the asset is assigned to the same customer.
     *
     *
     * @param  string  $id
     * @return self
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getAssetById(string $id): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetId']);

        return $this->fill($this->api()->get("asset/{$id}")->json());
    }

    /**
     * Fetch the Asset Info object based on the provided Asset Id.
     * If the user has the authority of 'Tenant Administrator', the server checks that the asset is owned by the same tenant.
     * If the user has the authority of 'Customer User', the server checks that the asset is assigned to the same customer.
     * Asset Info is an extension of the default Asset object that contains information about the assigned customer name.
     *
     * @param  string  $id
     * @return self
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getAssetInfoById(string $id): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetId']);

        return $this->fill($this->api()->get("asset/info/{$id}")->json());
    }

    /**
     * Returns a set of unique asset types based on assets that are either owned by the tenant or assigned
     * to the customer which user is performing the request.
     *
     * @return array
     *
     * @author Sabiee
     */
    public function getAssetTypes(): array
    {
        return $this->api()->get('asset/types')->json();
    }

    /**
     * Requested assets must be owned by tenant or assigned to customer which user is performing the request.
     *
     * @param  array  $ids
     * @return Asset[]
     *
     * @author Sabiee
     */
    public function getAssetsByIds(array $ids): array
    {
        foreach ($ids as $id) {
            Thingsboard::validation(! Str::isUuid($id), 'array_of', ['attribute' => 'ids', 'struct' => 'uuid']);
        }

        $assets = $this->api()->get('assets', ['assetIds' => implode(',', $ids)])->json();

        return array_map(fn ($asset) => new Asset($asset), $assets);
    }

    /**
     * Creates assignment of the asset to customer.
     * Customer will be able to query asset afterwards.
     *
     * @param  string|null  $customerId
     * @param  string|null  $id
     * @return self
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function assignAssetToCustomer(string $customerId = null, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        $customerId = $customerId ?? $this->forceAttribute('customerId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'assetId']);

        $asset = $this->api()->post("customer/{$customerId}/asset/{$id}")->json();

        return $this->fill($asset);
    }

    /**
     * Clears assignment of the asset to customer. Customer will not be able to query asset afterwards.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function unassignAssetFromCustomer(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'deviceId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("customer/asset/{$id}")->successful();
    }

    /**
     * Returns a page of assets info objects assigned to customer.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * Asset Info is an extension of the default Asset object that contains information about the assigned customer name.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $customerId
     * @param  string|null  $type
     * @param  string|null  $assetProfileId
     * @return PaginatedResponse
     *
     * @author  Sabiee
     */
    public function getCustomerAssetInfos(PaginationArguments $paginationArguments, string $customerId = null, string $type = null, string $assetProfileId = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumAssetSortProperty::class);

        $response = $this->api()->get("customer/{$customerId}/assetInfos", $paginationArguments->queryParams([
            'type' => $type ?? @$this->type,
            'assetProfileId' => $assetProfileId ?? @$this->assetProfileId->id,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of assets objects assigned to customer.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $customerId
     * @param  string|null  $type
     * @return PaginatedResponse
     *
     * @author  Sabiee
     */
    public function getCustomerAssets(PaginationArguments $paginationArguments, string $customerId = null, string $type = null): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumAssetSortProperty::class);

        $response = $this->api()->get("/api/customer/{$customerId}/assets", $paginationArguments->queryParams([
            'type' => $type ?? @$this->type,
        ]));

        return $this->paginatedResponse($response, $paginationArguments);
    }
}