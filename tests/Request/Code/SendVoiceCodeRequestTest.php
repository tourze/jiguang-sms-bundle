<?php

namespace JiguangSmsBundle\Tests\Request\Code;

use HttpClientBundle\Test\RequestTestCase;
use JiguangSmsBundle\Request\Code\SendVoiceCodeRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(SendVoiceCodeRequest::class)]
final class SendVoiceCodeRequestTest extends RequestTestCase
{
    private SendVoiceCodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new SendVoiceCodeRequest();
    }

    public function testSetAndGetMobile(): void
    {
        $mobile = '13800138000';
        $this->request->setMobile($mobile);

        $this->assertEquals($mobile, $this->request->getMobile());
    }

    public function testSetAndGetCode(): void
    {
        $code = '1234';
        $this->request->setCode($code);

        $this->assertEquals($code, $this->request->getCode());
    }

    public function testSetAndGetTtl(): void
    {
        $ttl = 300;
        $this->request->setTtl($ttl);

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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('mobile', $json);
        $this->assertEquals('13800138000', $json['mobile']);
        $this->assertArrayNotHasKey('code', $json);
        $this->assertArrayNotHasKey('ttl', $json);
    }

    public function testGetRequestOptionsWithAllParams(): void
    {
        $this->request->setMobile('13800138000');
        $this->request->setCode('1234');
        $this->request->setTtl(300);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('13800138000', $json['mobile']);
        $this->assertEquals('1234', $json['code']);
        $this->assertEquals(300, $json['ttl']);
    }
}
