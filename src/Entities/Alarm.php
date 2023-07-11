<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumAlarmSearchStatus;
use JalalLinuX\Thingsboard\Enums\EnumAlarmSeverityList;
use JalalLinuX\Thingsboard\Enums\EnumAlarmSortProperty;
use JalalLinuX\Thingsboard\Enums\EnumAlarmStatus;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $tenantId
 * @property Id $customerId
 * @property string $name
 * @property string $type
 * @property Id $originator
 * @property EnumAlarmSeverityList $severity
 * @property bool $acknowledged
 * @property bool $cleared
 * @property Id $assigneeId
 * @property \DateTime $startTs
 * @property \DateTime $endTs
 * @property \DateTime $ackTs
 * @property \DateTime $clearTs
 * @property \DateTime $assignTs
 * @property array $details
 * @property bool $propagate
 * @property bool $propagateToOwner
 * @property bool $propagateToTenant
 * @property array $propagateRelationTypes
 * @property string $typeList
 * @property EnumAlarmStatus $statusList
 */
class Alarm extends Tntity
{
    protected $fillable = [
        'id',
        'tenantId',
        'customerId',
        'name',
        'type',
        'originator',
        'severity',
        'acknowledged',
        'cleared',
        'assigneeId',
        'startTs',
        'endTs',
        'ackTs',
        'clearTs',
        'assignTs',
        'details',
        'propagate',
        'propagateToOwner',
        'propagateToTenant',
        'propagateRelationTypes',
        //----------------------
        'searchStatus',
        'status',
        'startTime',
        'endTime',
        'fetchOriginator',
        'typeList',
        'statusList',
    ];

    protected $casts = [
        'id' => CastId::class,
        'tenantId' => CastId::class,
        'customerId' => CastId::class,
        'originator' => CastId::class,
        'severity' => EnumAlarmSeverityList::class,
        'acknowledged' => 'boolean',
        'cleared' => 'boolean',
        'assigneeId' => CastId::class,
        'startTs' => 'timestamp',
        'endTs' => 'timestamp',
        'ackTs' => 'timestamp',
        'clearTs' => 'timestamp',
        'assignTs' => 'timestamp',
        'details' => 'array',
        'propagate' => 'boolean',
        'propagateToOwner' => 'boolean',
        'propagateToTenant' => 'boolean',
        'propagateRelationTypes' => 'array',
        'searchStatus' => EnumAlarmSearchStatus::class,
        'status' => EnumAlarmStatus::class,
        'startTime' => 'timestamp',
        'endTime' => 'timestamp',
        'fetchOriginator' => 'boolean',
        'statusList' => EnumAlarmStatus::class,
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::ALARM();
    }

    /**
     * Returns a page of alarms for the selected entity.
     * Specifying both parameters 'searchStatus' and 'status' at the same time will cause an error.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  Id|null  $id
     * @param  EnumAlarmSearchStatus|null  $searchStatus
     * @param  EnumAlarmStatus|null  $status
     * @param  string|null  $assigneeId
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @param  bool|null  $fetchOriginator
     * @return array
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getAlarms(PaginationArguments $paginationArguments, Id $id = null,
        EnumAlarmSearchStatus $searchStatus = null, EnumAlarmStatus $status = null,
        string $assigneeId = null, \DateTime $startTime = null, \DateTime $endTime = null,
        bool $fetchOriginator = null): array
    {
        $paginationArguments->validateSortProperty(EnumAlarmSortProperty::class);
        $id = $id ?? $this->forceAttribute('id');

        $queryParams = array_merge($paginationArguments->queryParams(), array_filter_null([
            'assigneeId' => $assigneeId ?? $this->getAttribute('assigneeId'),
            'startTime' => ! is_null($startTime) ? $startTime->getTimestamp() * 1000 : (! is_null($this->startTs) ? $startTime = $this->getAttribute('startTime') * 1000 : null),
            'endTime' => (! is_null($startTime) && is_null($endTime)) ? $this->forceAttribute('endTime') * 1000 : (! is_null($endTime) ? $endTime->getTimestamp() * 1000 : null),
            'fetchOriginator' => $fetchOriginator ?? $this->getAttribute('fetchOriginator'),
            'searchStatus' => $searchStatus ?? $this->getAttribute('searchStatus'),
            'status' => $status ?? $this->getAttribute('status'),
        ]));

        return $this->api()->get("alarm/{$id->entityType}/{$id->id}", $queryParams)->json();
    }

    /**
     * Returns a page of alarms for the selected entity.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  Id|null  $id
     * @param  EnumAlarmStatus|null  $statusList
     * @param  EnumAlarmSeverityList|null  $severityList
     * @param  array  $typeList
     * @param  string|null  $assigneeId
     * @param  \DateTime|null  $startTime
     * @param  \DateTime|null  $endTime
     * @return array
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function getAlarmsV2(PaginationArguments $paginationArguments, Id $id = null, EnumAlarmStatus $statusList = null,
        EnumAlarmSeverityList $severityList = null, array $typeList = [], string $assigneeId = null,
        \DateTime $startTime = null, \DateTime $endTime = null): array
    {
        $paginationArguments->validateSortProperty(EnumAlarmSortProperty::class);
        $id = $id ?? $this->forceAttribute('id');
        $typeList = (empty($typeList) ? $this->getAttribute('typeList') : implode(',', $typeList));
        $queryParams = array_merge($paginationArguments->queryParams(), array_filter_null([
            'assigneeId' => $assigneeId ?? $this->getAttribute('assigneeId'),
            'statusList' => $statusList ?? $this->getAttribute('statusList'),
            'startTime' => ! is_null($startTime) ? $startTime->getTimestamp() * 1000 : (! is_null($this->startTs) ? $startTime = $this->getAttribute('startTime') * 1000 : null),
            'endTime' => (! is_null($startTime) && is_null($endTime)) ? $this->forceAttribute('endTime') * 1000 : (! is_null($endTime) ? $endTime->getTimestamp() * 1000 : null),
            'severityList' => $severityList ?? $this->getAttribute('severityList'),
            'typeList' => $typeList,
        ]));

        return $this->api(true, true, 'v2')->get("alarm/{$id->entityType}/{$id->id}", $queryParams)->json();
    }

    /**
     * Creates or Updates the Alarm. When creating alarm, platform generates Alarm Id as time-based UUID.
     * The newly created Alarm id will be present in the response. Specify existing Alarm id to update the alarm.
     * Referencing non-existing Alarm Id will cause 'Not Found' error.
     *
     * Platform also deduplicate the alarms based on the entity id of originator and alarm 'type'.
     * For example, if the user or system component create the alarm with the type 'HighTemperature' for device 'Device A' the new active alarm is created.
     * If the user tries to create 'HighTemperature' alarm for the same device again, the previous alarm will
     * be updated (the 'end_ts' will be set to current timestamp). If the user clears the alarm (see 'Clear Alarm(clearAlarm)'),
     * than new alarm with the same type and same device may be created.
     * Remove 'id', 'tenantId' and optionally 'customerId' from the request body example (below) to create new Alarm entity.
     *
     * @param  string|null  $type
     * @param  Id|null  $originator
     * @param  EnumAlarmSeverityList|null  $severity
     * @return self
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function saveAlarm(string $type = null, Id $originator = null, EnumAlarmSeverityList $severity = null): static
    {
        $payload = array_merge($this->attributesToArray(), [
            'type' => $type ?? $this->forceAttribute('type'),
            'originator' => $originator ?? $this->forceAttribute('originator')->toArray(),
            'severity' => $severity->value ?? $this->forceAttribute('severity')->value,
        ]);

        $alarm = $this->api()->post('alarm', $payload)->json();

        return $this->fill($alarm);
    }

    /**
     * Deletes the Alarm. Referencing non-existing Alarm Id will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN | CUSTOMER_USER
     */
    public function deleteAlarm(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'alarmId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("alarm/{$id}")->successful();
    }
}
