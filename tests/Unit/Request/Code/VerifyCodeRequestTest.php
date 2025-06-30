<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Code;

use JiguangSmsBundle\Request\Code\VerifyCodeRequest;
use PHPUnit\Framework\TestCase;

class VerifyCodeRequestTest extends TestCase
{
    private VerifyCodeRequest $request;

    protected function setUp(): void
    {
        $this->request = new VerifyCodeRequest();
    }

    public function testSetAndGetMsgId(): void
    {
        $msgId = 'test-msg-id';
        $result = $this->request->setMsgId($msgId);

        $this->assertSame($this->request, $result);
        $this->assertEquals($msgId, $this->request->getMsgId());
    }

    public function testSetAndGetCode(): void
    {
        $code = '1234';
        $result = $this->request->setCode($code);

        $this->assertSame($this->request, $result);
        $this->assertEquals($code, $this->request->getCode());
    }

    public function testGetRequestPath(): void
    {
        $msgId = 'test-msg-id';
        $this->request->setMsgId($msgId);

        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/codes/test-msg-id/valid', $path);
    }

    public function testGetRequestOptions(): void
    {
        $this->request->setCode('1234');

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('code', $options['json']);
        $this->assertEquals('1234', $options['json']['code']);
    }
}