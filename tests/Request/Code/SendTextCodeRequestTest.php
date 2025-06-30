<?php

namespace JiguangSmsBundle\Tests\Request\Code;

use JiguangSmsBundle\Request\Code\SendTextCodeRequest;
use PHPUnit\Framework\TestCase;

class SendTextCodeRequestTest extends TestCase
{
    public function test_constructor_setsDefaultValues(): void
    {
        $request = new SendTextCodeRequest();

        $this->assertNull($request->getSignId());
        $this->assertNull($request->getTempId());
    }

    public function test_settersAndGetters_workCorrectly(): void
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

    public function test_getRequestPath_returnsCorrectPath(): void
    {
        $request = new SendTextCodeRequest();

        $this->assertEquals('https://api.sms.jpush.cn/v1/codes', $request->getRequestPath());
    }

    public function test_getRequestOptions_returnsCorrectOptions(): void
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

    public function test_getRequestOptions_withoutOptionalFields_returnsMinimalOptions(): void
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