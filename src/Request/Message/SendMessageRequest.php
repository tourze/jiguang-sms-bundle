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

    /** @var array<string, mixed>|null */
    protected ?array $tempPara = null;

    protected ?string $tag = null;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getTempId(): int
    {
        return $this->tempId;
    }

    public function setTempId(int $tempId): void
    {
        $this->tempId = $tempId;
    }

    public function getSignId(): ?int
    {
        return $this->signId;
    }

    public function setSignId(?int $signId): void
    {
        $this->signId = $signId;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getTempPara(): ?array
    {
        return $this->tempPara;
    }

    /**
     * @param array<string, mixed>|null $tempPara
     */
    public function setTempPara(?array $tempPara): void
    {
        $this->tempPara = $tempPara;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): void
    {
        $this->tag = $tag;
    }

    public function getRequestPath(): string
    {
        return 'https://api.sms.jpush.cn/v1/messages';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $params = [
            'mobile' => $this->mobile,
            'temp_id' => $this->tempId,
        ];

        if (null !== $this->signId) {
            $params['sign_id'] = $this->signId;
        }

        if (null !== $this->tempPara) {
            $params['temp_para'] = $this->tempPara;
        }

        if (null !== $this->tag) {
            $params['tag'] = $this->tag;
        }

        return [
            'json' => $params,
        ];
    }
}
