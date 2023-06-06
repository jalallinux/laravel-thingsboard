<?php

namespace JalalLinuX\Thingsboard\Entities;

use Illuminate\Support\Str;
use JalalLinuX\Thingsboard\Casts\CastConnection;
use JalalLinuX\Thingsboard\Casts\CastId;
use JalalLinuX\Thingsboard\Casts\CastNode;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Enums\EnumRuleChainSortProperty;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginatedResponse;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Infrastructure\RuleChain\Node;
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
 * @property Node $node
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
        'createdTime',
        'additionalInfo',
        'root',
        'debugMode',
        'configuration',
        'nodes',
        'connections',
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
        'nodes' => CastNode::class,
        'connections' => CastConnection::class,
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
        $payload = array_merge($this->attributes, [
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
     * @return PaginatedResponse
     *
     * @author  Sabiee
     *
     * @group TENANT_ADMIN
     */
    public function getRuleChains(PaginationArguments $paginationArguments, string $type = null): PaginatedResponse
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

        return $this->fill($this->api()->get("ruleChain/{$id}/metadata/")->json());
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
}
