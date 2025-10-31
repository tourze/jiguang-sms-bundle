<?php

namespace JiguangSmsBundle\Request\Code;

use JiguangSmsBundle\Request\WithAccountRequest;

/**
 * @see https://docs.jiguang.cn/jsms/server/rest_api_jsms#%E9%AA%8C%E8%AF%81%E7%A0%81%E9%AA%8C%E8%AF%81-api
 */
class VerifyCodeRequest extends WithAccountRequest
{
    protected string $msgId;

    protected string $code;

    public function getMsgId(): string
    {
        return $this->msgId;
    }

    public function setMsgId(string $msgId): void
    {
        $this->msgId = $msgId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getRequestPath(): string
    {
        return sprintf('https://api.sms.jpush.cn/v1/codes/%s/valid', $this->msgId);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'code' => $this->code,
            ],
        ];
    }
}
