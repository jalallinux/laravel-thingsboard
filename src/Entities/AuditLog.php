<?php

namespace JalalLinuX\Thingsboard\Entities;

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
     *
     *
     *
     *
     *
     *
     * @author JalalLinuX
     *
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
}
