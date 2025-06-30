<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Message;

use JiguangSmsBundle\Request\Message\SendMessageRequest;
use PHPUnit\Framework\TestCase;

class SendMessageRequestTest extends TestCase
{
    private SendMessageRequest $request;

    protected function setUp(): void
    {
        $this->request = new SendMessageRequest();
    }

    public function testSetAndGetMobile(): void
    {
        $mobile = '13800138000';
        $result = $this->request->setMobile($mobile);

        $this->assertSame($this->request, $result);
        $this->assertEquals($mobile, $this->request->getMobile());
    }

    public function testSetAndGetTempId(): void
    {
        $tempId = 123;
        $result = $this->request->setTempId($tempId);

        $this->assertSame($this->request, $result);
        $this->assertEquals($tempId, $this->request->getTempId());
    }

    public function testSetAndGetSignId(): void
    {
        $signId = 456;
        $result = $this->request->setSignId($signId);

        $this->assertSame($this->request, $result);
        $this->assertEquals($signId, $this->request->getSignId());
    }

    public function testSetAndGetTempPara(): void
    {
        $tempPara = ['param1' => 'value1', 'param2' => 'value2'];
        $result = $this->request->setTempPara($tempPara);

        $this->assertSame($this->request, $result);
        $this->assertEquals($tempPara, $this->request->getTempPara());
    }

    public function testSetAndGetTag(): void
    {
        $tag = 'test-tag';
        $result = $this->request->setTag($tag);

        $this->assertSame($this->request, $result);
        $this->assertEquals($tag, $this->request->getTag());
    }

    public function testGetRequestPath(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/messages', $path);
    }

    public function testGetRequestOptionsWithRequiredParams(): void
    {
        $this->request->setMobile('13800138000')
            ->setTempId(123);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertEquals('13800138000', $options['json']['mobile']);
        $this->assertEquals(123, $options['json']['temp_id']);
        $this->assertArrayNotHasKey('sign_id', $options['json']);
        $this->assertArrayNotHasKey('temp_para', $options['json']);
        $this->assertArrayNotHasKey('tag', $options['json']);
    }

    public function testGetRequestOptionsWithAllParams(): void
    {
        $this->request->setMobile('13800138000')
            ->setTempId(123)
            ->setSignId(456)
            ->setTempPara(['param1' => 'value1'])
            ->setTag('test-tag');

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertEquals('13800138000', $options['json']['mobile']);
        $this->assertEquals(123, $options['json']['temp_id']);
        $this->assertEquals(456, $options['json']['sign_id']);
        $this->assertEquals(['param1' => 'value1'], $options['json']['temp_para']);
        $this->assertEquals('test-tag', $options['json']['tag']);
    }
}