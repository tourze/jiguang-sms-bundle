<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Template;

use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Request\Template\AbstractTemplateRequest;
use JiguangSmsBundle\Request\Template\UpdateTemplateRequest;
use PHPUnit\Framework\TestCase;

class UpdateTemplateRequestTest extends TestCase
{
    private UpdateTemplateRequest $request;

    protected function setUp(): void
    {
        $this->request = new UpdateTemplateRequest();
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
            ->willReturn(789);

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
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('getTemplate')
            ->willReturn('updated-template');

        $template->expects($this->once())
            ->method('getType')
            ->willReturn(TemplateTypeEnum::VERIFICATION);

        $template->expects($this->once())
            ->method('getTtl')
            ->willReturn(600);

        $template->expects($this->once())
            ->method('getRemark')
            ->willReturn('updated-remark');

        $this->request->setTemplate($template);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertEquals('updated-template', $options['json']['template']);
        $this->assertEquals(TemplateTypeEnum::VERIFICATION->value, $options['json']['type']);
        $this->assertEquals(600, $options['json']['ttl']);
        $this->assertEquals('updated-remark', $options['json']['remark']);
    }
}
