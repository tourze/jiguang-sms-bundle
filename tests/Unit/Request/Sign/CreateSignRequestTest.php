<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Sign;

use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Request\Sign\AbstractSignRequest;
use JiguangSmsBundle\Request\Sign\CreateSignRequest;
use PHPUnit\Framework\TestCase;

class CreateSignRequestTest extends TestCase
{
    private CreateSignRequest $request;

    protected function setUp(): void
    {
        $this->request = new CreateSignRequest();
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
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('POST', $method);
    }

    public function testGetRequestOptionsBasic(): void
    {
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('getSign')
            ->willReturn('test-sign');

        $sign->expects($this->once())
            ->method('getType')
            ->willReturn(SignTypeEnum::COMPANY);

        $sign->expects($this->once())
            ->method('getRemark')
            ->willReturn(null);

        $sign->expects($this->once())
            ->method('getImage0')
            ->willReturn(null);

        $sign->expects($this->once())
            ->method('getImage1')
            ->willReturn(null);

        $this->request->setSign($sign);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('multipart', $options);
        $this->assertCount(2, $options['multipart']);
        $this->assertEquals('test-sign', $options['multipart'][0]['contents']);
        $this->assertEquals(SignTypeEnum::COMPANY->value, $options['multipart'][1]['contents']);
    }
}