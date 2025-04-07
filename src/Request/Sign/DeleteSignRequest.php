<?php

namespace JiguangSmsBundle\Request\Sign;

use JiguangSmsBundle\Entity\Sign;

class DeleteSignRequest extends AbstractSignRequest
{
    private Sign $sign;

    public function getRequestPath(): string
    {
        return sprintf('%s/%d', $this->getBaseUrl(), $this->sign->getSignId());
    }

    public function getRequestMethod(): ?string
    {
        return 'DELETE';
    }

    public function getRequestOptions(): ?array
    {
        return null;
    }

    public function getSign(): Sign
    {
        return $this->sign;
    }

    public function setSign(Sign $sign): self
    {
        $this->sign = $sign;
        return $this;
    }
} 