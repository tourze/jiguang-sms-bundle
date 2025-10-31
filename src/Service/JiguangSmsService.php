<?php

namespace JiguangSmsBundle\Service;

use HttpClientBundle\Client\ApiClient;
use HttpClientBundle\Request\RequestInterface;
use HttpClientBundle\Service\SmartHttpClient;
use JiguangSmsBundle\Request\WithAccountRequest;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;
use Yiisoft\Json\Json;

/**
 * 极光短信服务
 *
 * @see https://docs.jiguang.cn/jsms/server/rest_jsms_api_account
 */
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'jiguang_sms')]
class JiguangSmsService extends ApiClient
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SmartHttpClient $httpClient,
        private readonly LockFactory $lockFactory,
        private readonly CacheInterface $cache,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly AsyncInsertService $asyncInsertService,
    ) {
    }

    protected function getLockFactory(): LockFactory
    {
        return $this->lockFactory;
    }

    protected function getHttpClient(): SmartHttpClient
    {
        return $this->httpClient;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function getCache(): CacheInterface
    {
        return $this->cache;
    }

    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    protected function getAsyncInsertService(): AsyncInsertService
    {
        return $this->asyncInsertService;
    }

    protected function getRequestUrl(RequestInterface $request): string
    {
        return $request->getRequestPath();
    }

    protected function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?? 'POST';
    }

    /**
     * @return array<array-key, mixed>
     */
    protected function getRequestOptions(RequestInterface $request): ?array
    {
        $options = $request->getRequestOptions();
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        if ($request instanceof WithAccountRequest && is_array($options['headers'])) {
            $account = $request->getAccount();
            $options['headers']['Authorization'] = 'Basic ' . base64_encode($account->getAppKey() . ':' . $account->getMasterSecret());
        }

        return $options;
    }

    /**
     * @return array<string, mixed>
     */
    protected function formatResponse(RequestInterface $request, ResponseInterface $response): array
    {
        $decoded = Json::decode($response->getContent());
        if (!is_array($decoded)) {
            return [];
        }

        // 确保键为字符串类型，符合 array<string, mixed> 要求
        $result = [];
        foreach ($decoded as $key => $value) {
            $result[(string) $key] = $value;
        }

        return $result;
    }
}
