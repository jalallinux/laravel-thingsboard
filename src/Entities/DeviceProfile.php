<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\Id;
use JalalLinuX\Thingsboard\Enums\DeviceProfileSortProperty;
use JalalLinuX\Thingsboard\Enums\ThingsboardEntityType;
use JalalLinuX\Thingsboard\ThingsboardId;
use JalalLinuX\Thingsboard\ThingsboardPaginatedResponse;
use JalalLinuX\Thingsboard\ThingsboardPaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property ThingsboardId $id;
 * @property \DateTime $createdTime;
 * @property bool $default;
 * @property string $name;
 * @property string $type;
 * @property string $description;
 * @property string $image;
 * @property string $defaultQueueName;
 * @property string $provisionDeviceKey;
 * @property string $transportType;
 * @property string $provisionType;
 * @property array $profileData;
 * @property ThingsboardId $tenantId;
 * @property ThingsboardId $defaultDashboardId;
 * @property ThingsboardId $defaultRuleChainId;
 * @property ThingsboardId $firmwareId;
 * @property ThingsboardId $softwareId;
 * @property ThingsboardId $defaultEdgeRuleChainId;
 */
class DeviceProfile extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'default',
        'name',
        'type',
        'description',
        'image',
        'defaultQueueName',
        'provisionDeviceKey',
        'transportType',
        'provisionType',
        'profileData',
        'tenantId',
        'defaultDashboardId',
        'defaultRuleChainId',
        'firmwareId',
        'softwareId',
        'defaultEdgeRuleChainId',
    ];

    protected $casts = [
        'id'=> Id::class,
        'createdTime' => 'timestamp',
        'additionalInfo' => 'array',
        'customerId'=> Id::class,
        'deviceProfileId'=> Id::class,
        'deviceData' => 'array',
        'tenantId'=> Id::class,
        'firmwareId'=> Id::class,
        'softwareId'=> Id::class,
        'externalId'=> Id::class,
    ];

    public function entityType(): ?ThingsboardEntityType
    {
        return ThingsboardEntityType::DEVICE_PROFILE();
    }

    public function getDeviceProfiles(ThingsboardPaginationArguments $paginationArguments): ThingsboardPaginatedResponse
    {
        $paginationArguments->validateSortProperty(DeviceProfileSortProperty::class);

        $response = $this->api(true)->get('deviceProfiles', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Get Device Profile
     *
     * @throws \Throwable
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getDeviceProfileById(string $id = null): self
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        throw_if(
            ! Str::isUuid($id),
            $this->exception('method argument must be a valid uuid.'),
        );

        $deviceProfile = $this->api(true)->get("/deviceProfile/{$id}")->json();

        return tap($this, fn () => $this->fill($deviceProfile));
    }
}
