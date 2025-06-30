<?php

namespace JiguangSmsBundle\Tests\Unit\Request\Message;

use JiguangSmsBundle\Request\Message\GetStatusRequest;
use PHPUnit\Framework\TestCase;

class GetStatusRequestTest extends TestCase
{
    private GetStatusRequest $request;

    protected function setUp(): void
    {
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