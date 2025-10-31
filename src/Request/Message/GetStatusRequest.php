<?php

namespace JiguangSmsBundle\Request\Message;

use JiguangSmsBundle\Request\WithAccountRequest;

/**
 * @see https://docs.jiguang.cn/jsms/server/rest_api_jsms_inquire
 */
class GetStatusRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return 'https://api.sms.jpush.cn/v1/report';
    }

    public function getRequestMethod(): ?string
    {
        return 'GET';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return null;
    }
}
