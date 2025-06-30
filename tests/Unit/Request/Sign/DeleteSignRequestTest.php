<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Sign;

use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Request\Sign\AbstractSignRequest;
use JiguangSmsBundle\Request\Sign\DeleteSignRequest;
use PHPUnit\Framework\TestCase;

class DeleteSignRequestTest extends TestCase
{
    private DeleteSignRequest $request;

    protected function setUp(): void
    {
        $this->request = new DeleteSignRequest();
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
            ->willReturn(123);

        $this->request->setSign($sign);

        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign/123', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('DELETE', $method);
    }

    public function testGetRequestOptions(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertNull($options);
    }
}