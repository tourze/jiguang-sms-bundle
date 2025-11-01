<?php

namespace JiguangSmsBundle\Tests\Request\Message;

use HttpClientBundle\Test\RequestTestCase;
use JiguangSmsBundle\Request\Message\SendMessageRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(SendMessageRequest::class)]
final class SendMessageRequestTest extends RequestTestCase
{
    private SendMessageRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new SendMessageRequest();
    }

    public function testSetAndGetMobile(): void
    {
        $mobile = '13800138000';
        $this->request->setMobile($mobile);

        $this->assertEquals($mobile, $this->request->getMobile());
    }

    public function testSetAndGetTempId(): void
    {
        $tempId = 123;
        $this->request->setTempId($tempId);

        $this->assertEquals($tempId, $this->request->getTempId());
    }

    public function testSetAndGetSignId(): void
    {
        $signId = 456;
        $this->request->setSignId($signId);

        $this->assertEquals($signId, $this->request->getSignId());
    }

    public function testSetAndGetTempPara(): void
    {
        $tempPara = ['param1' => 'value1', 'param2' => 'value2'];
        $this->request->setTempPara($tempPara);

        $this->assertEquals($tempPara, $this->request->getTempPara());
    }

    public function testSetAndGetTag(): void
    {
        $tag = 'test-tag';
        $this->request->setTag($tag);

        $this->assertEquals($tag, $this->request->getTag());
    }

    public function testGetRequestPath(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/messages', $path);
    }

    public function testGetRequestOptionsWithRequiredParams(): void
    {
        $this->request->setMobile('13800138000');
        $this->request->setTempId(123);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('13800138000', $json['mobile']);
        $this->assertEquals(123, $json['temp_id']);
        $this->assertArrayNotHasKey('sign_id', $json);
        $this->assertArrayNotHasKey('temp_para', $json);
        $this->assertArrayNotHasKey('tag', $json);
    }

    public function testGetRequestOptionsWithAllParams(): void
    {
        $this->request->setMobile('13800138000');
        $this->request->setTempId(123);
        $this->request->setSignId(456);
        $this->request->setTempPara(['param1' => 'value1']);
        $this->request->setTag('test-tag');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('13800138000', $json['mobile']);
        $this->assertEquals(123, $json['temp_id']);
        $this->assertEquals(456, $json['sign_id']);
        $this->assertEquals(['param1' => 'value1'], $json['temp_para']);
        $this->assertEquals('test-tag', $json['tag']);
    }
}
