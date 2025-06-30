<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Template;

use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Request\Template\AbstractTemplateRequest;
use JiguangSmsBundle\Request\Template\DeleteTemplateRequest;
use PHPUnit\Framework\TestCase;

class DeleteTemplateRequestTest extends TestCase
{
    private DeleteTemplateRequest $request;

    protected function setUp(): void
    {
        $this->request = new DeleteTemplateRequest();
    }

    public function testExtendsAbstractTemplateRequest(): void
    {
        $this->assertInstanceOf(AbstractTemplateRequest::class, $this->request);
    }

    public function testSetAndGetTemplate(): void
    {
        $template = $this->createMock(Template::class);
        $result = $this->request->setTemplate($template);

        $this->assertSame($this->request, $result);
        $this->assertSame($template, $this->request->getTemplate());
    }

    public function testGetRequestPath(): void
    {
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('getTempId')
            ->willReturn(123);

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
