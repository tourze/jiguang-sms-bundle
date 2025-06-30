<?php

namespace JiguangSmsBundle\Service;

use HttpClientBundle\Client\ApiClient;
use HttpClientBundle\Request\RequestInterface;
use JiguangSmsBundle\Request\WithAccountRequest;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yiisoft\Json\Json;

/**
 * 极光短信服务
 *
 * @see https://docs.jiguang.cn/jsms/server/rest_jsms_api_account
 */
class JiguangSmsService extends ApiClient
{
    protected function getRequestUrl(RequestInterface $request): string
    {
        return $request->getRequestPath();
    }

    protected function getRequestMethod(RequestInterface $request): string
    {
        return $request->getRequestMethod() ?? 'POST';
    }

    protected function getRequestOptions(RequestInterface $request): ?array
    {
        $options = $request->getRequestOptions();
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        if ($request instanceof WithAccountRequest) {
            $options['headers']['Authorization'] = 'Basic ' . base64_encode($request->getAccount()->getAppKey() . ':' . $request->getAccount()->getMasterSecret());
        }

        return $options;
    }

    protected function formatResponse(RequestInterface $request, ResponseInterface $response): array
    {
        return Json::decode($response->getContent());
    }
}
