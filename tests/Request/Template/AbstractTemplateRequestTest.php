<?php

namespace JiguangSmsBundle\Tests\Request\Template;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Request\Template\AbstractTemplateRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(AbstractTemplateRequest::class)]
final class AbstractTemplateRequestTest extends RequestTestCase
{
    private AbstractTemplateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new class extends AbstractTemplateRequest {
            public function getRequestPath(): string
            {
                return $this->getBaseUrl();
            }

            /**
             * @return array<string, mixed>|null
             */
            public function getRequestOptions(): ?array
            {
                return null;
            }
        };
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertNotNull($this->request);
    }

    public function testGetBaseUrl(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/templates', $path);
    }
}
