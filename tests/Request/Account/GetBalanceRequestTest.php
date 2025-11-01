<?php

namespace JiguangSmsBundle\Tests\Request\Account;

use HttpClientBundle\Test\RequestTestCase;
use JiguangSmsBundle\Request\Account\GetBalanceRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GetBalanceRequest::class)]
final class GetBalanceRequestTest extends RequestTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 空实现，因为此测试不需要特殊的设置
    }

    public function testConstructorCreatesInstance(): void
    {
        $request = new GetBalanceRequest();

        $this->assertInstanceOf(GetBalanceRequest::class, $request);
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $request = new GetBalanceRequest();

        $this->assertEquals('https://api.sms.jpush.cn/v1/accounts/amount', $request->getRequestPath());
    }

    public function testGetRequestMethodReturnsGet(): void
    {
        $request = new GetBalanceRequest();

        $this->assertEquals('GET', $request->getRequestMethod());
    }
}
