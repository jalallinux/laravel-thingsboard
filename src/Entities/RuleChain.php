<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumRuleChainScriptLang;
use JalalLinuX\Thingsboard\Enums\EnumRuleChainSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\RuleChain\ImportStructure;
use JalalLinuX\Thingsboard\Thingsboard;
use JalalLinuX\Thingsboard\Tntity;

/**
 * @property Id $id
 * @property Id $ruleChainId
 * @property string $name
 * @property string $type
 * @property Id $tenantId
 * @property Id $firstRuleNodeId
 * @property \DateTime $createdTime
 * @property array $additionalInfo
 * @property bool $root
 * @property bool $debugMode
 * @property array $configuration
 * @property array $nodes
 * @property array $connections
 * @property array $ruleChainConnections
 */
class RuleChain extends Tntity
{
    protected $fillable = [
        'id',
        'ruleChainId',
        'name',
        'type',
        'tenantId',
        'firstRuleNodeId',
        'firstNodeIndex',
        'createdTime',
        'additionalInfo',
        'root',
        'debugMode',
        'configuration',
        'nodes',
        'connections',
        'ruleChainConnections',
    ];

    protected $casts = [
        'id' => CastId::class,
        'ruleChainId' => CastId::class,
        'tenantId' => CastId::class,
        'firstRuleNodeId' => CastId::class,
        'additionalInfo' => 'array',
        'configuration' => 'array',
        'firstNodeIndex' => 'integer',
        'createdTime' => 'timestamp',
        'root' => 'boolean',
        'debugMode' => 'boolean',
        'nodes' => 'array',
        'connections' => 'array',
        'ruleChainConnections' => 'array',
    ];

    public function entityType(): ?EnumEntityType
    {
        return EnumEntityType::RULE_CHAIN();
    }

    /**
     * Create or update the Rule Chain.
     * When creating Rule Chain, platform generates Rule Chain Id as time-based UUID.
     * The newly created Rule Chain Id will be present in the response.
     * Specify existing Rule Chain id to update the rule chain.
     * Referencing non-existing rule chain Id will cause 'Not Found' error.
     *
     * The rule chain object is lightweight and contains general information about the rule chain.
     * List of rule nodes and their connection is stored in a separate 'metadata' object.Remove 'id', 'tenantId' from
     * the request body example (below) to create new Rule Chain entity.
     *
     * @param  string|null  $name
     * @return self
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function saveRuleChain(string $name = null): static
    {
        $payload = array_merge($this->attributesToArray(), [
            'name' => $name ?? $this->forceAttribute('name'),
        ]);

        return tap($this, fn () => $this->fill($this->api()->post('ruleChain', $payload)->json()));
    }

    /**
     * Deletes the rule chain.
     * Referencing non-existing rule chain Id will cause an error.
     * Referencing rule chain that is used in the device profiles will cause an error.
     *
     * @param  string|null  $id
     * @return bool
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function deleteRuleChain(string $id = null): bool
    {
        $id = $id ?? $this->forceAttribute('id')->id ?? $this->forceAttribute('ruleChainId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleChainId']);

        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->delete("ruleChain/{$id}")->successful();
    }

    /**
     * Returns a page of Rule Chains owned by tenant.
     * The rule chain object is lightweight and contains general information about the rule chain.
     * List of rule nodes and their connection is stored in a separate 'metadata' object.You can specify parameters
     * to filter the results. The result is wrapped with PageData object that allows you to iterate over result set
     * using pagination. See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $type
     * @return LengthAwarePaginator
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getRuleChains(PaginationArguments $paginationArguments, string $type = null): LengthAwarePaginator
    {
        $paginationArguments->validateSortProperty(EnumRuleChainSortProperty::class);

        $response = $this->api()->get('ruleChains', $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }

    /**
     * Fetch the Rule Chain object based on the provided Rule Chain Id.
     * The rule chain object is lightweight and contains general information about the rule chain.
     * List of rule nodes and their connection is stored in a separate 'metadata' object.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getRuleChainById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id ?? $this->forceAttribute('ruleChainId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleChainId']);

        return $this->fill($this->api()->get("ruleChain/{$id}")->json());
    }

    /**
     * Fetch the Rule Chain Metadata object based on the provided Rule Chain Id.
     * The metadata object contains information about the rule nodes and their connections.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getRuleChainMetadataById(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id ?? $this->forceAttribute('ruleChainId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleChainId']);

        $ruleChain = $this->api()->get("ruleChain/{$id}/metadata/")->json();

        return $this->fill($ruleChain);
    }

    /**
     * Makes the rule chain to be root rule chain. Updates previous root rule chain as well.
     *
     * @param  string|null  $id
     * @return self
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function setRootRuleChain(string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id ?? $this->forceAttribute('ruleChainId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleChainId']);

        return $this->fill($this->api()->post("ruleChain/{$id}/root")->json());
    }

    /**
     * Create rule chain from template, based on the specified name in the request.
     * Creates the rule chain based on the template that is used to create root rule chain.
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function createDefaultRuleChain(string $name = null)
    {
        $payload = [
            'name' => $name ?? $this->forceAttribute('name'),
        ];

        return tap($this, fn () => $this->fill($this->api()->post('ruleChain/device/default', $payload)->json()));
    }

    /**
     * Returns 'True' if the TBEL script execution is enabled
     *
     * @return bool
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function isTBELScriptExecutorEnabled(): bool
    {
        return $this->api(handleException: config('thingsboard.rest.exception.throw_bool_methods'))->get('ruleChain/tbelEnabled')->successful();
    }

    /**
     * Execute the Script function and return the result. The format of request:
     * {
     *  "script": "Your Function as String",
     *  "scriptType": "One of: update, generate, filter, switch, json, string",
     *  "argNames": ["msg", "metadata", "type"],
     *  "msg": "{\"temperature\": 42}",
     *  "metadata": {
     *      "deviceName": "Device A",
     *      "deviceType": "Thermometer"
     * },
     *  "msgType": "POST_TELEMETRY_REQUEST"
     * }
     * Expected result JSON contains "output" and "error".
     *
     * @param  array  $script
     * @param  EnumRuleChainScriptLang|null  $scriptLang
     * @return array
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function testScriptFunction(array $script, EnumRuleChainScriptLang $scriptLang = null): array
    {
        return $this->api()->post('ruleChain/testScript'.(! is_null($scriptLang) ? "?scriptLang={$scriptLang}" : ''), $script)->json();
    }

    /**
     * Updates the rule chain metadata.
     * The metadata object contains information about the rule nodes and their connections.
     *
     * @param  bool|null  $updateRelated
     * @return self
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function updateRuleChainMetadata(bool $updateRelated = null): static
    {
        return tap($this, fn () => $this->fill($this->api()
            ->post('ruleChain/metadata'.(! is_null($updateRelated) ? "?updateRelated={$updateRelated}" : ''), $this->getCastAttributes())->json()));
    }

    /**
     * Exports all tenant rule chains as one JSON.
     *
     * @param  int  $limit
     * @return self[]
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function exportRuleChains(int $limit): array
    {
        $ruleChains = $this->api()->get('/ruleChains/export', ['limit' => $limit])->json();

        return array_map(fn ($ruleChain) => new self($ruleChain), $ruleChains);
    }

    /**
     * Imports all tenant rule chains as one JSON.
     *
     * @param  ImportStructure  $importStructure
     * @param  bool|null  $overwrite
     * @return array
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function importRuleChains(ImportStructure $importStructure, bool $overwrite = null): array
    {
        return $this->api()->post('ruleChains/import'.(! is_null($overwrite) ? "?overwrite={$overwrite}" : ''), $importStructure->toArray())->json();
    }

    /**
     * Gets the input message from the debug events for specified Rule Chain Id.
     * Referencing non-existing rule chain Id will cause an error.
     *
     * @param  string|null  $id
     * @return ?array
     *
     * @author Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getLatestRuleNodeDebugInput(string $id = null): ?array
    {
        $id = $id ?? $this->forceAttribute('id')->id ?? $this->forceAttribute('ruleNodeId')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleNodeId']);

        return $this->api()->get("ruleNode/{$id}/debugIn")->json();
    }

    /**
     * Creates assignment of an existing rule chain to an instance of The Edge.
     * Assignment works in async way - first, notification event pushed to edge service queue on platform.
     * Second, remote edge service will receive a copy of assignment rule chain
     * (Edge will receive this instantly, if it's currently connected, or once it's going to be connected to platform).
     * Third, once rule chain will be delivered to edge service, it's going to start processing messages locally.
     * Only rule chain with type 'EDGE' can be assigned to edge.
     *
     * @param  string  $edgeId
     * @param  string|null  $id
     * @return $this
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function assignRuleChainToEdge(string $edgeId, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleChainId']);
        Thingsboard::validation(! Str::isUuid($edgeId), 'uuid', ['attribute' => 'edgeId']);

        $ruleChain = $this->api()->post("edge/{$edgeId}/ruleChain/{$id}")->json();

        return $this->fill($ruleChain);
    }

    /**
     * Clears assignment of the rule chain to the edge.
     * Unassignment works in async way - first, 'unassign' notification event pushed to edge queue on platform.
     * Second, remote edge service will receive an 'unassign' command to remove rule chain
     * (Edge will receive this instantly, if it's currently connected, or once it's going to be connected to platform).
     * Third, once 'unassign' command will be delivered to edge service, it's going to remove rule chain locally.
     *
     * @param  string  $edgeId
     * @param  string|null  $id
     * @return $this
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function unassignRuleChainFromEdge(string $edgeId, string $id = null): static
    {
        $id = $id ?? $this->forceAttribute('id')->id;

        Thingsboard::validation(! Str::isUuid($id), 'uuid', ['attribute' => 'ruleChainId']);
        Thingsboard::validation(! Str::isUuid($edgeId), 'uuid', ['attribute' => 'edgeId']);

        $ruleChain = $this->api()->delete("edge/{$edgeId}/ruleChain/{$id}")->json();

        return $this->fill($ruleChain);
    }

    /**
     * Returns a page of Rule Chains assigned to the specified edge.
     * The rule chain object is lightweight and contains general information about the rule chain.
     * List of rule nodes and their connection is stored in a separate 'metadata' object.
     * You can specify parameters to filter the results.
     * The result is wrapped with PageData object that allows you to iterate over result set using pagination.
     * See the 'Model' tab of the Response Class for more details.
     *
     * @param  PaginationArguments  $paginationArguments
     * @param  string|null  $edgeId
     * @return LengthAwarePaginator
     *
     * @author JalalLinuX
     *
     * @group TENANT_ADMIN
     */
    public function getEdgeRuleChains(PaginationArguments $paginationArguments, string $edgeId = null): LengthAwarePaginator
    {
        $edgeId = $edgeId ?? $this->forceAttribute('id')->id;
        $paginationArguments->validateSortProperty(EnumRuleChainSortProperty::class);

        $response = $this->api()->get("edge/{$edgeId}/ruleChains", $paginationArguments->queryParams());

        return $this->paginatedResponse($response, $paginationArguments);
    }
}
