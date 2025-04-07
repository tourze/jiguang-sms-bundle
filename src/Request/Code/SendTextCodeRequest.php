<?php

namespace JiguangSmsBundle\Request\Code;

use JiguangSmsBundle\Request\WithAccountRequest;

/**
 * @see https://docs.jiguang.cn/jsms/server/rest_api_jsms#%E5%8F%91%E9%80%81%E6%96%87%E6%9C%AC%E9%AA%8C%E8%AF%81%E7%A0%81%E7%9F%AD%E4%BF%A1-api
 */
class SendTextCodeRequest extends WithAccountRequest
{
    protected string $mobile;
    protected ?int $signId = null;
    protected ?int $tempId = null;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getSignId(): ?int
    {
        return $this->signId;
    }

    public function setSignId(?int $signId): self
    {
        $this->signId = $signId;
        return $this;
    }

    public function getTempId(): ?int
    {
        return $this->tempId;
    }

    public function setTempId(?int $tempId): self
    {
        $this->tempId = $tempId;
        return $this;
    }

    public function getRequestPath(): string
    {
        return 'https://api.sms.jpush.cn/v1/codes';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'mobile' => $this->mobile,
        ];

        if ($this->signId !== null) {
            $params['sign_id'] = $this->signId;
        }

        if ($this->tempId !== null) {
            $params['temp_id'] = $this->tempId;
        }

        return [
            'json' => $params,
        ];
    }
}
