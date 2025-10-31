<?php

namespace JiguangSmsBundle\Request\Account;

use JiguangSmsBundle\Request\WithAccountRequest;

/**
 * @see https://docs.jiguang.cn/jsms/server/rest_jsms_api_account#%E5%BA%94%E7%94%A8%E4%BD%99%E9%87%8F%E6%9F%A5%E8%AF%A2-api
 */
class GetBalanceRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return 'https://api.sms.jpush.cn/v1/accounts/amount';
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
