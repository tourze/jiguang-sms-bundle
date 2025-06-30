<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Sign;

use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Request\Sign\AbstractSignRequest;
use JiguangSmsBundle\Request\Sign\GetSignRequest;
use PHPUnit\Framework\TestCase;

class GetSignRequestTest extends TestCase
{
    private GetSignRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetSignRequest();
    }

    public function testExtendsAbstractSignRequest(): void
    {
        $this->assertInstanceOf(AbstractSignRequest::class, $this->request);
    }

    public function testSetAndGetSign(): void
    {
        $sign = $this->createMock(Sign::class);
        $result = $this->request->setSign($sign);

        $this->assertSame($this->request, $result);
        $this->assertSame($sign, $this->request->getSign());
    }

    public function testGetRequestPath(): void
    {
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('getSignId')
            ->willReturn(456);

        $this->request->setSign($sign);

        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign/456', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('GET', $method);
    }

    public function testGetRequestOptions(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertNull($options);
    }
}