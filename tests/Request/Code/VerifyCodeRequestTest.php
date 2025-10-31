<?php

namespace JiguangSmsBundle\Tests\Request\Code;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Request\Code\VerifyCodeRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * @internal
 */
#[CoversClass(VerifyCodeRequest::class)]
final class VerifyCodeRequestTest extends RequestTestCase
{
    private VerifyCodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new VerifyCodeRequest();
    }

    public function testSetAndGetMsgId(): void
    {
        $msgId = 'test-msg-id';
        $this->request->setMsgId($msgId);

        $this->assertEquals($msgId, $this->request->getMsgId());
    }

    public function testSetAndGetCode(): void
    {
        $code = '1234';
        $this->request->setCode($code);

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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('code', $json);
        $this->assertEquals('1234', $json['code']);
    }
}
