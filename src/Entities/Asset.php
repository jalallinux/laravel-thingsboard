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
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'assetProfileId' => CastId::class,
        'additionalInfo' => 'array',
        'createdTime' => 'timestamp',
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
     * @param  string  $id
     * @return bool
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function deleteAsset(string $id): bool
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
     * @param string $id
     * @return Asset
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
}
