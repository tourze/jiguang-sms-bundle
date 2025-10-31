<?php

namespace JiguangSmsBundle\Tests\Request\Sign;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Request\Sign\AbstractSignRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(AbstractSignRequest::class)]
final class AbstractSignRequestTest extends RequestTestCase
{
    private AbstractSignRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new class extends AbstractSignRequest {
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

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign', $path);
    }
}
