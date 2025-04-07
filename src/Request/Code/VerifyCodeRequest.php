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

    public function setMsgId(string $msgId): self
    {
        $this->msgId = $msgId;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getRequestPath(): string
    {
        return sprintf('https://api.sms.jpush.cn/v1/codes/%s/valid', $this->msgId);
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'code' => $this->code,
            ],
        ];
    }
}
