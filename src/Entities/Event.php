<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumEventSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumEventType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property array $id
 * @property Id $tenantId
 * @property EnumEventType $type
 * @property string $uid
 * @property Id $entityId
 * @property array $body
 * @property \DateTime $createdTime
 */
class Event extends Tntity
{
    protected $fillable = [
        'id',
        'tenantId',
        'type',
        'uid',
        'entityId',
        'body',
        'createdTime'
    ];

    protected $casts = [
        'id' => 'array',
        'tenantId' => CastId::class,
        'type' => EnumEventType::class,
        'entityId' => CastId::class,
        'body' => 'array',
        'createdTime' => 'timestamp',
    ];

    public function entityType(): ?EnumEntityType
    {
        return null;
    }

    /**
     * Returns a page of events for the chosen entity by specifying the event filter. You can specify parameters to filter the results. The result is wrapped with PageData object that allows you to iterate over result set using pagination. See the 'Model' tab of the Response Class for more details.
     *
     * Event Filter Definition
     * 5 different eventFilter objects could be set for different event types. The eventType field is required. Others are optional. If some of them are set, the filtering will be applied according to them. See the examples below for all the fields used for each event type filtering.
     *
     * Note,
     *
     * 'server' - string value representing the server name, identifier or ip address where the platform is running;
     * 'errorStr' - the case insensitive 'contains' filter based on error message.
     * Error Event Filter
     * {
     * "eventType":"ERROR",
     * "server":"ip-172-31-24-152",
     * "method":"onClusterEventMsg",
     * "errorStr":"Error Message"
     * }
     * 'method' - string value representing the method name when the error happened.
     * Lifecycle Event Filter
     * {
     * "eventType":"LC_EVENT",
     * "server":"ip-172-31-24-152",
     * "event":"STARTED",
     * "status":"Success",
     * "errorStr":"Error Message"
     * }
     * 'event' - string value representing the lifecycle event type;
     * 'status' - string value representing status of the lifecycle event.
     * Statistics Event Filter
     * {
     * "eventType":"STATS",
     * "server":"ip-172-31-24-152",
     * "messagesProcessed":10,
     * "errorsOccurred":5
     * }
     * 'messagesProcessed' - the minimum number of successfully processed messages;
     * 'errorsOccurred' - the minimum number of errors occurred during messages processing.
     * Debug Rule Node Event Filter
     * {
     * "eventType":"DEBUG_RULE_NODE",
     * "msgDirectionType":"IN",
     * "server":"ip-172-31-24-152",
     * "dataSearch":"humidity",
     * "metadataSearch":"deviceName",
     * "entityName":"DEVICE",
     * "relationType":"Success",
     * "entityId":"de9d54a0-2b7a-11ec-a3cc-23386423d98f",
     * "msgType":"POST_TELEMETRY_REQUEST",
     * "isError":"false",
     * "errorStr":"Error Message"
     * }
     * Debug Rule Chain Event Filter
     * {
     * "eventType":"DEBUG_RULE_CHAIN",
     * "msgDirectionType":"IN",
     * "server":"ip-172-31-24-152",
     * "dataSearch":"humidity",
     * "metadataSearch":"deviceName",
     * "entityName":"DEVICE",
     * "relationType":"Success",
     * "entityId":"de9d54a0-2b7a-11ec-a3cc-23386423d98f",
     * "msgType":"POST_TELEMETRY_REQUEST",
     * "isError":"false",
     * "errorStr":"Error Message"
     * }
     * 'msgDirectionType' - string value representing msg direction type (incoming to entity or outcoming from entity);
     * 'dataSearch' - the case insensitive 'contains' filter based on data (key and value) for the message;
     * 'metadataSearch' - the case insensitive 'contains' filter based on metadata (key and value) for the message;
     * 'entityName' - string value representing the entity type;
     * 'relationType' - string value representing the type of message routing;
     * 'entityId' - string value representing the entity id in the event body (originator of the message);
     * 'msgType' - string value representing the message type;
     * 'isError' - boolean value to filter the errors.
     *
     * @param PaginationArguments $paginationArguments
     * @param Id $id
     * @param string $tenantId
     * @param \DateTime|null $startTime
     * @param \DateTime|null $endTime
     * @return LengthAwarePaginator
     *
     * @author  Sabiee
     *
     * @group
     */
    public function getEventsByEventFilter(PaginationArguments $paginationArguments, Id $id, string $tenantId, \DateTime $startTime = null, \DateTime $endTime = null): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumEventSortProperty::class);

        Thingsboard::validation(! Str::isUuid($tenantId), 'uuid', ['attribute' => 'tenantId']);

        $queryParams = array_filter_null([
            'tenantId' => $tenantId,
        ]);

        $queryParams = array_merge($queryParams, $paginationArguments->queryParams());

        if (! is_null($startTime)) {
            $endTime = @$endTime ?? now();
            Thingsboard::validation($startTime->getTimestamp() > $endTime->getTimestamp(), 'before', [
                'attribute' => 'start time', 'date' => 'end time',
            ]);
            $queryParams = array_merge($queryParams, [
                'startTime' => $startTime->getTimestamp() * 1000,
                'endTime' => $endTime->getTimestamp() * 1000,
            ]);
        }

        $queryParams = http_build_query($queryParams);

        $response = $this->api()->post("events/{$id->entityType}/{$id->id}?{$queryParams}", $this->getAttribute('body'));
        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Returns a page of events for specified entity by specifying event type.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @author  Sabiee
     */
    public function getEventsByType(PaginationArguments $paginationArguments, Id $id, EnumEventType $eventType, string $tenantId, \DateTime $startTime = null, \DateTime $endTime = null): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumEventSortProperty::class);

        Thingsboard::validation(! Str::isUuid($tenantId), 'uuid', ['attribute' => 'tenantId']);

        $queryParams = array_filter_null([
            'tenantId' => $tenantId,
        ]);

        $queryParams = array_merge($queryParams, $paginationArguments->queryParams());

        if (! is_null($startTime)) {
            $endTime = @$endTime ?? now();
            Thingsboard::validation($startTime->getTimestamp() > $endTime->getTimestamp(), 'before', [
                'attribute' => 'start time', 'date' => 'end time',
            ]);
            $queryParams = array_merge($queryParams, [
                'startTime' => $startTime->getTimestamp() * 1000,
                'endTime' => $endTime->getTimestamp() * 1000,
            ]);
        }

        $queryParams = http_build_query($queryParams);

        $response = $this->api()->get("events/{$id->entityType}/{$id->id}/{$eventType}?{$queryParams}");

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Clears events by filter for specified entity.
     *
     * @param  Id  $id
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @return bool
     *
     * @author  Sabiee
     */
    public function clearEvents(Id $id, \DateTime $startTime = null, \DateTime $endTime = null): bool
    {
        $queryParams = null;
        Thingsboard::validation(! Str::isUuid($id->id), 'uuid', ['attribute' => 'entityId']);

        if (! is_null($startTime)) {
            $endTime = @$endTime ?? now();
            Thingsboard::validation($startTime->getTimestamp() > $endTime->getTimestamp(), 'before', [
                'attribute' => 'start time', 'date' => 'end time',
            ]);
            $queryParams = [
                'startTime' => $startTime->getTimestamp() * 1000,
                'endTime' => $endTime->getTimestamp() * 1000,
            ];
            $queryParams = http_build_query($queryParams);
        }

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->post("events/{$id->entityType}/{$id->id}/clear".(! is_null($queryParams) ? "?{$queryParams}" : ''), $this->getAttribute('body'))->successful();
    }
}
