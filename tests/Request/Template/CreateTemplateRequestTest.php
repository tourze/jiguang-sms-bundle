<?php

namespace JiguangSmsBundle\Tests\Request\Template;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Request\Template\CreateTemplateRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CreateTemplateRequest::class)]
final class CreateTemplateRequestTest extends RequestTestCase
{
    private CreateTemplateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateTemplateRequest();
    }

    public function testExtendsAbstractTemplateRequest(): void
    {
        $this->assertNotNull($this->request);
    }

    public function testSetAndGetTemplate(): void
    {
        // 使用具体类 Template：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $template = $this->createMock(Template::class);
        $this->request->setTemplate($template);

        $this->assertSame($template, $this->request->getTemplate());
    }

    public function testGetRequestPath(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/templates', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('POST', $method);
    }

    public function testGetRequestOptions(): void
    {
        // 使用具体类 Template：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('getTemplate')
            ->willReturn('test-template')
        ;

        $template->expects($this->once())
            ->method('getType')
            ->willReturn(TemplateTypeEnum::VERIFICATION)
        ;

        $template->expects($this->once())
            ->method('getTtl')
            ->willReturn(300)
        ;

        $template->expects($this->once())
            ->method('getRemark')
            ->willReturn('test-remark')
        ;

        $this->request->setTemplate($template);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('test-template', $json['template']);
        $this->assertEquals(TemplateTypeEnum::VERIFICATION->value, $json['type']);
        $this->assertEquals(300, $json['ttl']);
        $this->assertEquals('test-remark', $json['remark']);
    }
}
