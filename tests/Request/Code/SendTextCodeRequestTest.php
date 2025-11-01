<?php

namespace JiguangSmsBundle\Tests\Request\Code;

use HttpClientBundle\Test\RequestTestCase;
use JiguangSmsBundle\Request\Code\SendTextCodeRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(SendTextCodeRequest::class)]
final class SendTextCodeRequestTest extends RequestTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 空实现，因为此测试不需要特殊的设置
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $request = new SendTextCodeRequest();

        $this->assertNull($request->getSignId());
        $this->assertNull($request->getTempId());
    }

    public function testSettersAndGettersWorkCorrectly(): void
    {
        $request = new SendTextCodeRequest();
        $mobile = '13800138000';
        $signId = 123;
        $tempId = 456;

        $request->setMobile($mobile);
        $request->setSignId($signId);
        $request->setTempId($tempId);

        $this->assertEquals($mobile, $request->getMobile());
        $this->assertEquals($signId, $request->getSignId());
        $this->assertEquals($tempId, $request->getTempId());
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $request = new SendTextCodeRequest();

        $this->assertEquals('https://api.sms.jpush.cn/v1/codes', $request->getRequestPath());
    }

    public function testGetRequestOptionsReturnsCorrectOptions(): void
    {
        $request = new SendTextCodeRequest();
        $request->setMobile('13800138000');
        $request->setSignId(123);
        $request->setTempId(456);

        $expected = [
            'json' => [
                'mobile' => '13800138000',
                'sign_id' => 123,
                'temp_id' => 456,
            ],
        ];

        $this->assertEquals($expected, $request->getRequestOptions());
    }

    public function testGetRequestOptionsWithoutOptionalFieldsReturnsMinimalOptions(): void
    {
        $request = new SendTextCodeRequest();
        $request->setMobile('13800138000');

        $expected = [
            'json' => [
                'mobile' => '13800138000',
            ],
        ];

        $this->assertEquals($expected, $request->getRequestOptions());
    }
}
