<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Template;

use JiguangSmsBundle\Request\Template\AbstractTemplateRequest;
use JiguangSmsBundle\Request\WithAccountRequest;
use PHPUnit\Framework\TestCase;

class AbstractTemplateRequestTest extends TestCase
{
    private AbstractTemplateRequest $request;

    protected function setUp(): void
    {
        $this->request = new class extends AbstractTemplateRequest {
            public function getRequestPath(): string
            {
                return $this->getBaseUrl();
            }

            public function getRequestOptions(): ?array
            {
                return null;
            }
        };
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetBaseUrl(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/templates', $path);
    }
}