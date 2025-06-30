<?php

namespace JiguangSmsBundle\Tests\Request\Account;

use JiguangSmsBundle\Request\Account\GetBalanceRequest;
use PHPUnit\Framework\TestCase;

class GetBalanceRequestTest extends TestCase
{
    public function test_constructor_createsInstance(): void
    {
        $request = new GetBalanceRequest();
        
        $this->assertInstanceOf(GetBalanceRequest::class, $request);
    }

    public function test_getRequestPath_returnsCorrectPath(): void
    {
        $request = new GetBalanceRequest();
        
        $this->assertEquals('https://api.sms.jpush.cn/v1/accounts/amount', $request->getRequestPath());
    }

    public function test_getRequestMethod_returnsGet(): void
    {
        $request = new GetBalanceRequest();
        
        $this->assertEquals('GET', $request->getRequestMethod());
    }
} 