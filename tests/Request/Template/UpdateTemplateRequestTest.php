<?php

namespace JiguangSmsBundle\Tests\Request\Template;

use HttpClientBundle\Test\RequestTestCase;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Request\Template\UpdateTemplateRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdateTemplateRequest::class)]
final class UpdateTemplateRequestTest extends RequestTestCase
{
    private UpdateTemplateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UpdateTemplateRequest();
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
        // 使用具体类 Template：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('getTempId')
            ->willReturn(789)
        ;

        $this->request->setTemplate($template);

        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/templates/789', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('PUT', $method);
    }

    public function testGetRequestOptions(): void
    {
        // 使用具体类 Template：这是业务实体类，没有对应接口
        // 这种使用是合理的，因为实体类是数据模型的具体实现
        // 替代方案是创建接口，但实体类的接口化意义不大
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('getTemplate')
            ->willReturn('updated-template')
        ;

        $template->expects($this->once())
            ->method('getType')
            ->willReturn(TemplateTypeEnum::VERIFICATION)
        ;

        $template->expects($this->once())
            ->method('getTtl')
            ->willReturn(600)
        ;

        $template->expects($this->once())
            ->method('getRemark')
            ->willReturn('updated-remark')
        ;

        $this->request->setTemplate($template);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('updated-template', $json['template']);
        $this->assertEquals(TemplateTypeEnum::VERIFICATION->value, $json['type']);
        $this->assertEquals(600, $json['ttl']);
        $this->assertEquals('updated-remark', $json['remark']);
    }
}
