<?php

namespace JiguangSmsBundle\Tests\Request\Sign;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Request\Sign\GetSignRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GetSignRequest::class)]
final class GetSignRequestTest extends RequestTestCase
{
    private GetSignRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetSignRequest();
    }

    public function testExtendsAbstractSignRequest(): void
    {
        $this->assertNotNull($this->request);
    }

    public function testSetAndGetSign(): void
    {
        // 使用具体类 Sign：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $sign = $this->createMock(Sign::class);
        $this->request->setSign($sign);

        $this->assertSame($sign, $this->request->getSign());
    }

    public function testGetRequestPath(): void
    {
        // 使用具体类 Sign：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('getSignId')
            ->willReturn(456)
        ;

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
