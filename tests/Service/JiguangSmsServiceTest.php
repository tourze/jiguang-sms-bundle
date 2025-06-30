<?php

namespace JiguangSmsBundle\Tests\Service;

use HttpClientBundle\Request\RequestInterface;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Request\WithAccountRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yiisoft\Json\Json;

class JiguangSmsServiceTest extends TestCase
{
    private JiguangSmsService $service;

    protected function setUp(): void
    {
        $this->service = new JiguangSmsService();
    }

    public function test_getRequestMethod_withRequestMethod_returnsMethod(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestMethod')
            ->willReturn('GET');

        $result = $this->invokeMethod($this->service, 'getRequestMethod', [$request]);

        $this->assertEquals('GET', $result);
    }

    public function test_getRequestMethod_withNullMethod_returnsDefaultPost(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestMethod')
            ->willReturn(null);

        $result = $this->invokeMethod($this->service, 'getRequestMethod', [$request]);

        $this->assertEquals('POST', $result);
    }

    public function test_getRequestUrl_returnsRequestPath(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestPath')
            ->willReturn('/test/path');

        $result = $this->invokeMethod($this->service, 'getRequestUrl', [$request]);

        $this->assertEquals('/test/path', $result);
    }

    public function test_getRequestOptions_withoutAccountRequest_returnsOptionsWithHeaders(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestOptions')
            ->willReturn([]);

        $result = $this->invokeMethod($this->service, 'getRequestOptions', [$request]);

        $this->assertArrayHasKey('headers', $result);
        $this->assertIsArray($result['headers']);
    }

    public function testGetRequestUrl(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->method('getRequestPath')
            ->willReturn('/test/path');

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestUrl');
        $method->setAccessible(true);

        $url = $method->invoke($this->service, $request);
        $this->assertEquals('/test/path', $url);
    }

    public function testGetRequestMethod(): void
    {
        // 测试默认方法（POST）
        $request = $this->createMock(RequestInterface::class);
        $request->method('getRequestMethod')
            ->willReturn(null);

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestMethod');
        $method->setAccessible(true);

        $requestMethod = $method->invoke($this->service, $request);
        $this->assertEquals('POST', $requestMethod);

        // 测试自定义方法（GET）
        $requestWithMethod = $this->createMock(RequestInterface::class);
        $requestWithMethod->method('getRequestMethod')
            ->willReturn('GET');

        $requestMethod = $method->invoke($this->service, $requestWithMethod);
        $this->assertEquals('GET', $requestMethod);
    }

    public function testGetRequestOptions_withNormalRequest(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->method('getRequestOptions')
            ->willReturn(['timeout' => 30]);

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestOptions');
        $method->setAccessible(true);

        $options = $method->invoke($this->service, $request);
        $this->assertArrayHasKey('timeout', $options);
        $this->assertEquals(30, $options['timeout']);
        $this->assertArrayHasKey('headers', $options);
    }

    public function testGetRequestOptions_withAccountRequest(): void
    {
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $request = $this->getMockBuilder(WithAccountRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->method('getRequestOptions')
            ->willReturn([]);
        $request->method('getAccount')
            ->willReturn($account);

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestOptions');
        $method->setAccessible(true);

        $options = $method->invoke($this->service, $request);

        $expectedAuth = 'Basic ' . base64_encode('test_app_key:test_master_secret');
        $this->assertArrayHasKey('headers', $options);
        $this->assertArrayHasKey('Authorization', $options['headers']);
        $this->assertEquals($expectedAuth, $options['headers']['Authorization']);
    }

    public function testFormatResponse(): void
    {
        $responseData = ['key' => 'value'];
        $responseJson = Json::encode($responseData);

        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn($responseJson);

        $method = new \ReflectionMethod(JiguangSmsService::class, 'formatResponse');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $request, $response);
        $this->assertEquals($responseData, $result);
    }


    private function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
