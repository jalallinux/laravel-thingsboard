<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Enums\EnumAuditLogActionStatus;
use JalalLinuX\Thingsboard\Enums\EnumAuditLogActionType;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property int $createdTime
 * @property Id $tenantId
 * @property Id $customerId
 * @property Id $entityId
 * @property string $entityName
 * @property Id $userId
 * @property string $userName
 * @property EnumAuditLogActionType $actionType
 * @property array $actionData
 * @property EnumAuditLogActionStatus $actionStatus
 * @property string $actionFailureDetails
 */
class AuditLog extends Tntity
{
    protected $fillable = [
        'id',
        'createdTime',
        'tenantId',
        'customerId',
        'entityId',
        'entityName',
        'userId',
        'userName',
        'actionType',
        'actionData',
        'actionStatus',
        'actionFailureDetails',
    ];

    protected $casts = [
        'id' => Id::class,
        'createdTime' => 'timestamp',
        'tenantId' => Id::class,
        'customerId' => Id::class,
        'entityId' => Id::class,
        'userId' => Id::class,
        'actionType' => EnumAuditLogActionType::class,
        'actionData' => 'array',
        'actionStatus' => EnumAuditLogActionStatus::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Returns a page of audit logs related to all entities in the scope of the current user's Tenant.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * @param  PaginationArguments  $paginationArguments
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @param  EnumAuditLogActionType|null  $actionType
     * @return PaginatedResponse
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function getAuditLogs(PaginationArguments $paginationArguments, \DateTime $startTime = null, \DateTime $endTime = null, EnumAuditLogActionType $actionType = null): PaginatedResponse
    {
        $payload = $paginationArguments->queryParams([
            'actionType' => $actionType, 'startTime' => ! is_null($startTime) ? $startTime->getTimestamp() * 1000 : null, 'endTime' => ! is_null($endTime) ? $endTime->getTimestamp() * 1000 : null,
        ]);

        $response = $this->api()->get('audit/logs', $payload);

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of audit logs related to the targeted customer entities (devices, assets, etc.), and users actions (login, logout, etc.) that belong to this customer.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * @param  string  $customerId
     * @param  PaginationArguments  $paginationArguments
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @param  EnumAuditLogActionType|null  $actionType
     * @return PaginatedResponse
     * @throws \Throwable
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function getAuditLogsByCustomerId(string $customerId, PaginationArguments $paginationArguments, \DateTime $startTime = null, \DateTime $endTime = null, EnumAuditLogActionType $actionType = null): PaginatedResponse
    {
        throw_if(
            ! Str::isUuid($customerId),
            $this->exception('method "customerId" argument must be a valid uuid.'),
        );

        $payload = $paginationArguments->queryParams([
            'actionType' => $actionType, 'startTime' => ! is_null($startTime) ? $startTime->getTimestamp() * 1000 : null, 'endTime' => ! is_null($endTime) ? $endTime->getTimestamp() * 1000 : null,
        ]);

        $response = $this->api()->get("audit/logs/customer/{$customerId}", $payload);

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of audit logs related to the actions of targeted user.
     * For example, RPC call to a particular device, or alarm acknowledgment for a specific device, etc.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * @param  string  $userId
     * @param  PaginationArguments  $paginationArguments
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @param  EnumAuditLogActionType|null  $actionType
     * @return PaginatedResponse
     * @throws \Throwable
     * @author JalalLinuX
     */
    public function getAuditLogsByUserId(string $userId, PaginationArguments $paginationArguments, \DateTime $startTime = null, \DateTime $endTime = null, EnumAuditLogActionType $actionType = null): PaginatedResponse
    {
        throw_if(
            ! Str::isUuid($userId),
            $this->exception('method "userId" argument must be a valid uuid.'),
        );

        $payload = $paginationArguments->queryParams([
            'actionType' => $actionType, 'startTime' => ! is_null($startTime) ? $startTime->getTimestamp() * 1000 : null, 'endTime' => ! is_null($endTime) ? $endTime->getTimestamp() * 1000 : null,
        ]);

        $response = $this->api()->get("audit/logs/user/{$userId}", $payload);

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of audit logs related to the actions on the targeted entity.
     * Basically, this API call is used to get the full lifecycle of some specific entity.
     * For example to see when a device was created, updated, assigned to some customer, or even deleted from the system.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     * @param  Id  $entity
     * @param  PaginationArguments  $paginationArguments
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @param  EnumAuditLogActionType|null  $actionType
     * @return PaginatedResponse
     * @author JalalLinuX
     * @group TENANT_ADMIN
     */
    public function getAuditLogsByEntityId(Id $entity, PaginationArguments $paginationArguments, \DateTime $startTime = null, \DateTime $endTime = null, EnumAuditLogActionType $actionType = null): PaginatedResponse
    {
        $payload = $paginationArguments->queryParams([
            'actionType' => $actionType, 'startTime' => ! is_null($startTime) ? $startTime->getTimestamp() * 1000 : null, 'endTime' => ! is_null($endTime) ? $endTime->getTimestamp() * 1000 : null,
        ]);

        $response = $this->api()->get("audit/logs/entity/{$entity->entityType->value}/{$entity->id}", $payload);

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
