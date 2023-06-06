<?php

namespace JalalLinuX\Thingsboard\Entities;

use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumAssetProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $tenantId
 * @property string $name
 * @property bool $default
 * @property Id $defaultDashboardId
 * @property Id $defaultRuleChainId
 * @property string $defaultQueueName
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
        'additionalInfo' => 'array',
        'createdTime' => 'timestamp',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::ASSET_PROFILE();
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
    public function getAssetProfiles(PaginationArguments $paginationArguments): PaginatedResponse
    {
        $paginationArguments->validateSortProperty(EnumAssetProfileSortProperty::class);

        $response = $this->api()->get('assetProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
