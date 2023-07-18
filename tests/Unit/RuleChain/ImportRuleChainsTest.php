<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Infrastructure\RuleChain\ImportStructure;
use JalalLinuX\Thingsboard\Tests\TestCase;

class ImportRuleChainsTest extends TestCase
{
    public function testImportSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());

        $ruleChains = thingsboard($tenantUser)->ruleChain()->importRuleChains($this->ruleChainToImport());

        $this->assertIsArray($ruleChains);
        foreach ($ruleChains as $ruleChain) {
            $this->assertArrayHasKey('ruleChainId', $ruleChain);
            $this->assertArrayHasKey('ruleChainName', $ruleChain);
            thingsboard($tenantUser)->ruleChain()->deleteRuleChain($ruleChain['ruleChainId']['id']);
        }
    }

    public function ruleChainToImport(): ImportStructure
    {
        $ruleChains = [
            'ruleChains' => [
                [
                    'id' => [
                        'entityType' => 'RULE_CHAIN',
                        'id' => '646e7210-0501-11ee-a3e6-4bfaefbf3033',
                    ],
                    'createdTime' => 1685510131455,
                    'additionalInfo' => null,
                    'tenantId' => null,
                    'name' => 'Root Rule Chain',
                    'type' => 'CORE',
                    'firstRuleNodeId' => [
                        'entityType' => 'RULE_NODE',
                        'id' => '2a0d9040-ff72-11ed-b30f-fb731a74cac9',
                    ],
                    'root' => true,
                    'debugMode' => false,
                    'configuration' => null,
                    'externalId' => null,
                ],
                [
                    'id' => [
                        'entityType' => 'RULE_CHAIN',
                        'id' => '646e7211-0501-11ee-a3e6-4bfaefbf3033',
                    ],
                    'createdTime' => 1685510131540,
                    'additionalInfo' => [
                        'description' => '',
                    ],
                    'tenantId' => null,
                    'name' => 'Thermostat',
                    'type' => 'CORE',
                    'firstRuleNodeId' => [
                        'entityType' => 'RULE_NODE',
                        'id' => '2a12c060-ff72-11ed-b30f-fb731a74cac9',
                    ],
                    'root' => false,
                    'debugMode' => false,
                    'configuration' => null,
                    'externalId' => null,
                ],
            ],
            'metadata' => [
                [
                    'ruleChainId' => [
                        'entityType' => 'RULE_CHAIN',
                        'id' => '646e7210-0501-11ee-a3e6-4bfaefbf3033',
                    ],
                    'firstNodeIndex' => null,
                    'nodes' => [
                        [
                            'id' => null,
                            'createdTime' => 1686121148834,
                            'additionalInfo' => [
                                'layoutX' => 824,
                                'layoutY' => 156,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.telemetry.TbMsgTimeseriesNode',
                            'name' => 'Save Timeseries',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'defaultTTL' => 0,
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1686121148835,
                            'additionalInfo' => [
                                'layoutX' => 825,
                                'layoutY' => 52,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.telemetry.TbMsgAttributesNode',
                            'name' => 'Save Client Attributes',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'scope' => 'CLIENT_SCOPE',
                                'notifyDevice' => 'false',
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1686121148836,
                            'additionalInfo' => [
                                'layoutX' => 347,
                                'layoutY' => 149,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.filter.TbMsgTypeSwitchNode',
                            'name' => 'Message Type Switch',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'version' => 0,
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1686121148837,
                            'additionalInfo' => [
                                'layoutX' => 825,
                                'layoutY' => 266,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.action.TbLogNode',
                            'name' => 'Log RPC from Device',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'scriptLang' => 'TBEL',
                                'jsScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                                'tbelScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1686121148839,
                            'additionalInfo' => [
                                'layoutX' => 825,
                                'layoutY' => 379,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.action.TbLogNode',
                            'name' => 'Log Other',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'scriptLang' => 'TBEL',
                                'jsScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                                'tbelScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1686121148840,
                            'additionalInfo' => [
                                'layoutX' => 825,
                                'layoutY' => 480,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.rpc.TbSendRPCRequestNode',
                            'name' => 'RPC Call Request',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'timeoutInSeconds' => 60,
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1686121148841,
                            'additionalInfo' => [
                                'description' => 'Process incoming messages from devices with the alarm rules defined in the device profile. Dispatch all incoming messages with "Success" relation type.',
                                'layoutX' => 204,
                                'layoutY' => 240,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.profile.TbDeviceProfileNode',
                            'name' => 'Device Profile Node',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'persistAlarmRulesState' => false,
                                'fetchAlarmRulesStateOnStart' => false,
                            ],
                            'externalId' => null,
                        ],
                    ],
                    'connections' => [
                        [
                            'fromIndex' => 2,
                            'toIndex' => 0,
                            'type' => 'Post telemetry',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 1,
                            'type' => 'Post attributes',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 3,
                            'type' => 'RPC Request from Device',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 4,
                            'type' => 'Other',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 5,
                            'type' => 'RPC Request to Device',
                        ],
                        [
                            'fromIndex' => 6,
                            'toIndex' => 2,
                            'type' => 'Success',
                        ],
                    ],
                    'ruleChainConnections' => null,
                ],
                [
                    'ruleChainId' => [
                        'entityType' => 'RULE_CHAIN',
                        'id' => '646e7211-0501-11ee-a3e6-4bfaefbf3033',
                    ],
                    'firstNodeIndex' => 6,
                    'nodes' => [
                        [
                            'id' => null,
                            'createdTime' => 1685510131546,
                            'additionalInfo' => [
                                'layoutX' => 822,
                                'layoutY' => 294,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.telemetry.TbMsgTimeseriesNode',
                            'name' => 'Save Timeseries',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'defaultTTL' => 0,
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1685510131548,
                            'additionalInfo' => [
                                'layoutX' => 824,
                                'layoutY' => 221,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.telemetry.TbMsgAttributesNode',
                            'name' => 'Save Client Attributes',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'scope' => 'CLIENT_SCOPE',
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1685510131551,
                            'additionalInfo' => [
                                'layoutX' => 494,
                                'layoutY' => 309,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.filter.TbMsgTypeSwitchNode',
                            'name' => 'Message Type Switch',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'version' => 0,
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1685510131553,
                            'additionalInfo' => [
                                'layoutX' => 824,
                                'layoutY' => 383,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.action.TbLogNode',
                            'name' => 'Log RPC from Device',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'scriptLang' => 'TBEL',
                                'jsScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                                'tbelScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1685510131554,
                            'additionalInfo' => [
                                'layoutX' => 823,
                                'layoutY' => 444,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.action.TbLogNode',
                            'name' => 'Log Other',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'scriptLang' => 'TBEL',
                                'jsScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                                'tbelScript' => "return '\nIncoming message:\n' + JSON.stringify(msg) + '\nIncoming metadata:\n' + JSON.stringify(metadata);",
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1685510131556,
                            'additionalInfo' => [
                                'layoutX' => 822,
                                'layoutY' => 507,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.rpc.TbSendRPCRequestNode',
                            'name' => 'RPC Call Request',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'timeoutInSeconds' => 60,
                            ],
                            'externalId' => null,
                        ],
                        [
                            'id' => null,
                            'createdTime' => 1685510131558,
                            'additionalInfo' => [
                                'description' => '',
                                'layoutX' => 209,
                                'layoutY' => 307,
                            ],
                            'ruleChainId' => null,
                            'type' => 'org.thingsboard.rule.engine.profile.TbDeviceProfileNode',
                            'name' => 'Device Profile Node',
                            'debugMode' => false,
                            'singletonMode' => false,
                            'configuration' => [
                                'persistAlarmRulesState' => false,
                                'fetchAlarmRulesStateOnStart' => false,
                            ],
                            'externalId' => null,
                        ],
                    ],
                    'connections' => [
                        [
                            'fromIndex' => 2,
                            'toIndex' => 0,
                            'type' => 'Post telemetry',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 1,
                            'type' => 'Post attributes',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 3,
                            'type' => 'RPC Request from Device',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 4,
                            'type' => 'Other',
                        ],
                        [
                            'fromIndex' => 2,
                            'toIndex' => 5,
                            'type' => 'RPC Request to Device',
                        ],
                        [
                            'fromIndex' => 6,
                            'toIndex' => 2,
                            'type' => 'Success',
                        ],
                    ],
                    'ruleChainConnections' => null,
                ],
            ],
        ];

        return new ImportStructure([
            'ruleChains' => $ruleChains['ruleChains'],
            'metadata' => $ruleChains['metadata'],
        ]);
    }
}
