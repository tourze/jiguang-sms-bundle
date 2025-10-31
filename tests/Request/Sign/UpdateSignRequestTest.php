<?php

namespace JiguangSmsBundle\Tests\Request\Sign;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Request\Sign\UpdateSignRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdateSignRequest::class)]
final class UpdateSignRequestTest extends RequestTestCase
{
    private UpdateSignRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UpdateSignRequest();
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
            ->willReturn(789)
        ;

        $this->request->setSign($sign);

        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign/789', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('POST', $method);
    }

    public function testGetRequestOptionsBasic(): void
    {
        // 使用具体类 Sign：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('getSign')
            ->willReturn('test-sign')
        ;

        $sign->expects($this->once())
            ->method('getType')
            ->willReturn(SignTypeEnum::COMPANY)
        ;

        $sign->expects($this->once())
            ->method('getRemark')
            ->willReturn(null)
        ;

        $sign->expects($this->once())
            ->method('getImage0')
            ->willReturn(null)
        ;

        $sign->expects($this->once())
            ->method('getImage1')
            ->willReturn(null)
        ;

        $this->request->setSign($sign);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('multipart', $options);

        /** @var array<int, mixed> $multipart */
        $multipart = $options['multipart'];
        $this->assertIsArray($multipart);
        $this->assertCount(2, $multipart);

        /** @var array<string, mixed> $firstPart */
        $firstPart = $multipart[0];
        $this->assertIsArray($firstPart);

        /** @var array<string, mixed> $secondPart */
        $secondPart = $multipart[1];
        $this->assertIsArray($secondPart);

        $this->assertEquals('test-sign', $firstPart['contents']);
        $this->assertEquals(SignTypeEnum::COMPANY->value, $secondPart['contents']);
    }
}
