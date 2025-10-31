<?php

namespace JiguangSmsBundle\Tests\Service;

use HttpClientBundle\Request\RequestInterface;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Request\WithAccountRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Yiisoft\Json\Json;

/**
 * @internal
 */
#[CoversClass(JiguangSmsService::class)]
#[RunTestsInSeparateProcesses]
final class JiguangSmsServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 空实现，测试不需要特殊初始化
    }

    public function testGetRequestMethodWithRequestMethodReturnsMethod(): void
    {
        $service = self::getService(JiguangSmsService::class);
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestMethod')
            ->willReturn('GET')
        ;

        $result = $this->invokeMethod($service, 'getRequestMethod', [$request]);

        $this->assertEquals('GET', $result);
    }

    public function testGetRequestMethodWithNullMethodReturnsDefaultPost(): void
    {
        $service = self::getService(JiguangSmsService::class);
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestMethod')
            ->willReturn(null)
        ;

        $result = $this->invokeMethod($service, 'getRequestMethod', [$request]);

        $this->assertEquals('POST', $result);
    }

    public function testGetRequestUrlReturnsRequestPath(): void
    {
        $service = self::getService(JiguangSmsService::class);
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestPath')
            ->willReturn('/test/path')
        ;

        $result = $this->invokeMethod($service, 'getRequestUrl', [$request]);

        $this->assertEquals('/test/path', $result);
    }

    public function testGetRequestOptionsWithoutAccountRequestReturnsOptionsWithHeaders(): void
    {
        $service = self::getService(JiguangSmsService::class);
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->once())
            ->method('getRequestOptions')
            ->willReturn([])
        ;

        $result = $this->invokeMethod($service, 'getRequestOptions', [$request]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertIsArray($result['headers']);
    }

    public function testGetRequestUrl(): void
    {
        $service = self::getService(JiguangSmsService::class);
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->method('getRequestPath')
            ->willReturn('/test/path')
        ;

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestUrl');
        $method->setAccessible(true);

        $url = $method->invoke($service, $request);
        $this->assertEquals('/test/path', $url);
    }

    public function testGetRequestMethod(): void
    {
        $service = self::getService(JiguangSmsService::class);
        // 测试默认方法（POST）
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->method('getRequestMethod')
            ->willReturn(null)
        ;

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestMethod');
        $method->setAccessible(true);

        $requestMethod = $method->invoke($service, $request);
        $this->assertEquals('POST', $requestMethod);

        // 测试自定义方法（GET）
        /** @var MockObject&RequestInterface $requestWithMethod */
        $requestWithMethod = $this->createMock(RequestInterface::class);
        $requestWithMethod->method('getRequestMethod')
            ->willReturn('GET')
        ;

        $requestMethod = $method->invoke($service, $requestWithMethod);
        $this->assertEquals('GET', $requestMethod);
    }

    public function testGetRequestOptionsWithNormalRequest(): void
    {
        $service = self::getService(JiguangSmsService::class);
        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        $request->method('getRequestOptions')
            ->willReturn(['timeout' => 30])
        ;

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestOptions');
        $method->setAccessible(true);

        $options = $method->invoke($service, $request);
        $this->assertIsArray($options);
        $this->assertArrayHasKey('timeout', $options);
        $this->assertEquals(30, $options['timeout']);
        $this->assertArrayHasKey('headers', $options);
    }

    public function testGetRequestOptionsWithAccountRequest(): void
    {
        $service = self::getService(JiguangSmsService::class);
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        /** @var MockObject&WithAccountRequest $request */
        $request = $this->getMockBuilder(WithAccountRequest::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $request->method('getRequestOptions')
            ->willReturn([])
        ;
        $request->method('getAccount')
            ->willReturn($account)
        ;

        $method = new \ReflectionMethod(JiguangSmsService::class, 'getRequestOptions');
        $method->setAccessible(true);

        $options = $method->invoke($service, $request);

        $this->assertIsArray($options);
        $expectedAuth = 'Basic ' . base64_encode('test_app_key:test_master_secret');
        $this->assertArrayHasKey('headers', $options);
        $this->assertIsArray($options['headers']);
        $this->assertArrayHasKey('Authorization', $options['headers']);
        $this->assertEquals($expectedAuth, $options['headers']['Authorization']);
    }

    public function testFormatResponse(): void
    {
        $service = self::getService(JiguangSmsService::class);
        $responseData = ['key' => 'value'];
        $responseJson = Json::encode($responseData);

        /** @var MockObject&RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);
        /** @var MockObject&ResponseInterface $response */
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn($responseJson)
        ;

        $method = new \ReflectionMethod(JiguangSmsService::class, 'formatResponse');
        $method->setAccessible(true);

        $result = $method->invoke($service, $request, $response);
        $this->assertEquals($responseData, $result);
    }

    /**
     * @param array<int, mixed> $parameters
     */
    private function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
