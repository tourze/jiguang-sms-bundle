<?php

namespace JiguangSmsBundle\Request\Message;

use JiguangSmsBundle\Request\WithAccountRequest;

/**
 * @see https://docs.jiguang.cn/jsms/server/rest_api_jsms#%E5%8F%91%E9%80%81%E5%8D%95%E6%9D%A1%E6%A8%A1%E6%9D%BF%E7%9F%AD%E4%BF%A1-api
 */
class SendMessageRequest extends WithAccountRequest
{
    protected string $mobile;
    protected int $tempId;
    protected ?int $signId = null;
    protected ?array $tempPara = null;
    protected ?string $tag = null;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getTempId(): int
    {
        return $this->tempId;
    }

    public function setTempId(int $tempId): self
    {
        $this->tempId = $tempId;
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

    public function getTempPara(): ?array
    {
        return $this->tempPara;
    }

    public function setTempPara(?array $tempPara): self
    {
        $this->tempPara = $tempPara;
        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function getRequestPath(): string
    {
        return 'https://api.sms.jpush.cn/v1/messages';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'mobile' => $this->mobile,
            'temp_id' => $this->tempId,
        ];

        if ($this->signId !== null) {
            $params['sign_id'] = $this->signId;
        }

        if ($this->tempPara !== null) {
            $params['temp_para'] = $this->tempPara;
        }

        if ($this->tag !== null) {
            $params['tag'] = $this->tag;
        }

        return [
            'json' => $params,
        ];
    }
}
