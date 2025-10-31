<?php

namespace JiguangSmsBundle\Request\Code;

use JiguangSmsBundle\Request\WithAccountRequest;

/**
 * @see https://docs.jiguang.cn/jsms/server/rest_api_jsms#%E5%8F%91%E9%80%81%E8%AF%AD%E9%9F%B3%E9%AA%8C%E8%AF%81%E7%A0%81%E7%9F%AD%E4%BF%A1-api
 */
class SendVoiceCodeRequest extends WithAccountRequest
{
    protected string $mobile;

    protected ?string $code = null;

    protected ?int $ttl = null;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(?int $ttl): void
    {
        $this->ttl = $ttl;
    }

    public function getRequestPath(): string
    {
        return 'https://api.sms.jpush.cn/v1/voice_codes';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $params = [
            'mobile' => $this->mobile,
        ];

        if (null !== $this->code) {
            $params['code'] = $this->code;
        }

        if (null !== $this->ttl) {
            $params['ttl'] = $this->ttl;
        }

        return [
            'json' => $params,
        ];
    }
}
