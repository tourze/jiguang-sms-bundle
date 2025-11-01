<?php

namespace JiguangSmsBundle\Tests\Request\Message;

use HttpClientBundle\Test\RequestTestCase;
use JiguangSmsBundle\Request\Message\GetStatusRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GetStatusRequest::class)]
final class GetStatusRequestTest extends RequestTestCase
{
    private GetStatusRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetStatusRequest();
    }

    public function testGetRequestPath(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/report', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('GET', $method);
    }

    public function testGetRequestOptions(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertNull($options);
    }
}
