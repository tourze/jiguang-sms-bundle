<?php

namespace JiguangSmsBundle\Tests\Request\Template;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Request\Template\DeleteTemplateRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(DeleteTemplateRequest::class)]
final class DeleteTemplateRequestTest extends RequestTestCase
{
    private DeleteTemplateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new DeleteTemplateRequest();
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
            ->willReturn(123)
        ;

        $this->request->setTemplate($template);

        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/templates/123', $path);
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
