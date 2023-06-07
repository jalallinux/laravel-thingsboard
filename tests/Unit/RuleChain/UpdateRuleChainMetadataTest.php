<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use Illuminate\Support\Arr;
use JalalLinuX\Thingsboard\Entities\RuleChain;
use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumEntityType;
use JalalLinuX\Thingsboard\Infrastructure\Id;
use JalalLinuX\Thingsboard\Infrastructure\PaginationArguments;
use JalalLinuX\Thingsboard\Tests\TestCase;

class UpdateRuleChainMetadataTest extends TestCase
{
    public function testCreateDeviceSuccess()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $attributes = [
            'name' => $this->faker->sentence(3),
        ];
        $ruleChain = thingsboard($tenantUser)->ruleChain($attributes)->saveRuleChain();

        $this->assertInstanceOf(RuleChain::class, $ruleChain);
        $this->assertInstanceOf(Id::class, $ruleChain->id);
        $this->assertEquals($attributes['name'], $ruleChain->name);

        $result = thingsboard($tenantUser)->ruleChain($this->ruleNodesToUpdate($ruleChain->id->id))->updateRuleChainMetadata();

        $this->assertEquals(5, $result->getAttribute('firstNodeIndex'));
        $ruleChain->deleteRuleChain();
    }

    public function ruleNodesToUpdate(string $id)
    {
        return [
            "ruleChainId" => [
                "entityType" => "RULE_CHAIN",
                "id" => $id
            ],
            "nodes" => [
                [
                    "type" => "org.thingsboard.rule.engine.telemetry.TbMsgTimeseriesNode",
                    "name" => "Save Timeseries",
                    "configuration" => [
                        "defaultTTL" => 0
                    ],
                    "additionalInfo" => [
                        "layoutX" => 935,
                        "layoutY" => 272
                    ],
                    "debugMode" => false
                ],
                [
                    "type" => "org.thingsboard.rule.engine.filter.TbMsgTypeSwitchNode",
                    "name" => "Message Type Switch",
                    "configuration" => [
                        "version" => 0
                    ],
                    "additionalInfo" => [
                        "layoutX" => 458,
                        "layoutY" => 265
                    ],
                    "debugMode" => false
                ],
                [
                    "type" => "org.thingsboard.rule.engine.action.TbLogNode",
                    "name" => "Log RPC from Device",
                    "configuration" => [
                        "scriptLang" => "TBEL",
                        "jsScript" => "return '
Incoming message:
' + JSON.stringify(msg) + '
Incoming metadata:
' + JSON.stringify(metadata);",
                        "tbelScript" => "return '
Incoming message:
' + JSON.stringify(msg) + '
Incoming metadata:
' + JSON.stringify(metadata);"
                    ],
                    "additionalInfo" => [
                        "layoutX" => 936,
                        "layoutY" => 382
                    ],
                    "debugMode" => false
                ],
                [
                    "type" => "org.thingsboard.rule.engine.action.TbLogNode",
                    "name" => "Log Other",
                    "configuration" => [
                        "scriptLang" => "TBEL",
                        "jsScript" => "return '
Incoming message:
' + JSON.stringify(msg) + '
Incoming metadata:
' + JSON.stringify(metadata);",
                        "tbelScript" => "return '
Incoming message:
' + JSON.stringify(msg) + '
Incoming metadata:
' + JSON.stringify(metadata);"
                    ],
                    "additionalInfo" => [
                        "layoutX" => 936,
                        "layoutY" => 495
                    ],
                    "debugMode" => false
                ],
                [
                    "type" => "org.thingsboard.rule.engine.rpc.TbSendRPCRequestNode",
                    "name" => "RPC Call Request",
                    "configuration" => [
                        "timeoutInSeconds" => 60
                    ],
                    "additionalInfo" => [
                        "layoutX" => 936,
                        "layoutY" => 596
                    ],
                    "debugMode" => false
                ],
                [
                    "type" => "org.thingsboard.rule.engine.profile.TbDeviceProfileNode",
                    "name" => "Device Profile Node",
                    "configuration" => [
                        "persistAlarmRulesState" => false,
                        "fetchAlarmRulesStateOnStart" => false
                    ],
                    "additionalInfo" => [
                        "description" => "Process incoming messages from devices with the alarm rules defined in the device profile. Dispatch all incoming messages with \"Success\" relation type.",
                        "layoutX" => 315,
                        "layoutY" => 356
                    ],
                    "debugMode" => false
                ]
            ],
            "connections" => [
                [
                    "fromIndex" => 1,
                    "toIndex" => 0,
                    "type" => "Post telemetry"
                ],
                [
                    "fromIndex" => 1,
                    "toIndex" => 2,
                    "type" => "RPC Request from Device"
                ],
                [
                    "fromIndex" => 1,
                    "toIndex" => 3,
                    "type" => "Other"
                ],
                [
                    "fromIndex" => 1,
                    "toIndex" => 4,
                    "type" => "RPC Request to Device"
                ],
                [
                    "fromIndex" => 5,
                    "toIndex" => 1,
                    "type" => "Success"
                ]
            ],
            "firstNodeIndex" => 5
        ];


    }
}
