<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Template;

use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Request\Template\AbstractTemplateRequest;
use JiguangSmsBundle\Request\Template\CreateTemplateRequest;
use PHPUnit\Framework\TestCase;

class CreateTemplateRequestTest extends TestCase
{
    private CreateTemplateRequest $request;

    protected function setUp(): void
    {
        $this->request = new CreateTemplateRequest();
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
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('getTemplate')
            ->willReturn('test-template');

        $template->expects($this->once())
            ->method('getType')
            ->willReturn(TemplateTypeEnum::VERIFICATION);

        $template->expects($this->once())
            ->method('getTtl')
            ->willReturn(300);

        $template->expects($this->once())
            ->method('getRemark')
            ->willReturn('test-remark');

        $this->request->setTemplate($template);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertEquals('test-template', $options['json']['template']);
        $this->assertEquals(TemplateTypeEnum::VERIFICATION->value, $options['json']['type']);
        $this->assertEquals(300, $options['json']['ttl']);
        $this->assertEquals('test-remark', $options['json']['remark']);
    }
}