<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Code;

use JiguangSmsBundle\Request\Code\SendVoiceCodeRequest;
use PHPUnit\Framework\TestCase;

class SendVoiceCodeRequestTest extends TestCase
{
    private SendVoiceCodeRequest $request;

    protected function setUp(): void
    {
        $this->request = new SendVoiceCodeRequest();
    }

    public function testSetAndGetMobile(): void
    {
        $mobile = '13800138000';
        $result = $this->request->setMobile($mobile);

        $this->assertSame($this->request, $result);
        $this->assertEquals($mobile, $this->request->getMobile());
    }

    public function testSetAndGetCode(): void
    {
        $code = '1234';
        $result = $this->request->setCode($code);

        $this->assertSame($this->request, $result);
        $this->assertEquals($code, $this->request->getCode());
    }

    public function testSetAndGetTtl(): void
    {
        $ttl = 300;
        $result = $this->request->setTtl($ttl);

        $this->assertSame($this->request, $result);
        $this->assertEquals($ttl, $this->request->getTtl());
    }

    public function testGetRequestPath(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/voice_codes', $path);
    }

    public function testGetRequestOptionsWithMobileOnly(): void
    {
        $this->request->setMobile('13800138000');

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('mobile', $options['json']);
        $this->assertEquals('13800138000', $options['json']['mobile']);
        $this->assertArrayNotHasKey('code', $options['json']);
        $this->assertArrayNotHasKey('ttl', $options['json']);
    }

    public function testGetRequestOptionsWithAllParams(): void
    {
        $this->request->setMobile('13800138000')
            ->setCode('1234')
            ->setTtl(300);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertEquals('13800138000', $options['json']['mobile']);
        $this->assertEquals('1234', $options['json']['code']);
        $this->assertEquals(300, $options['json']['ttl']);
    }
}