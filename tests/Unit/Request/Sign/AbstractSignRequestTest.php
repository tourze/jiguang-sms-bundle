<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Sign;

use JiguangSmsBundle\Request\Sign\AbstractSignRequest;
use JiguangSmsBundle\Request\WithAccountRequest;
use PHPUnit\Framework\TestCase;

class AbstractSignRequestTest extends TestCase
{
    private AbstractSignRequest $request;

    protected function setUp(): void
    {
        $this->request = new class extends AbstractSignRequest {
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

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign', $path);
    }
}