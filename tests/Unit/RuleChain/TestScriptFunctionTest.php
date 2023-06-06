<?php

namespace JalalLinuX\Thingsboard\Tests\Unit\RuleChain;

use JalalLinuX\Thingsboard\Enums\EnumAuthority;
use JalalLinuX\Thingsboard\Enums\EnumRuleChainScriptLang;
use JalalLinuX\Thingsboard\Tests\TestCase;

class TestScriptFunctionTest extends TestCase
{
    public function testCorrectUuid()
    {
        $tenantUser = $this->thingsboardUser(EnumAuthority::TENANT_ADMIN());
        $result = thingsboard($tenantUser)->ruleChain()->testScriptFunction($this->bodyForTest(), EnumRuleChainScriptLang::JS());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('output', $result);
        $this->assertArrayHasKey('error', $result);
    }

    public function bodyForTest(): array
    {
        return [
            'argNames' => [
                'prevMsg',
                'prevMetadata',
                'prevMsgType',
            ],
            'scriptType' => 'generate',
            'msgType' => 'POST_TELEMETRY_REQUEST',
            'msg' => '{"temperature": 22.4,"humidity": 78}',
            'metadata' => [
                'deviceName' => 'Test Device',
                'deviceType' => 'default',
                'ts' => '1686055195721',
            ],
            'script' => 'var msg = { temp: 42, humidity: 77 }; var metadata = { data: 40 }; var msgType = "POST_TELEMETRY_REQUEST"; return { msg: msg, metadata: metadata, msgType: msgType };',
        ];

    }
}
