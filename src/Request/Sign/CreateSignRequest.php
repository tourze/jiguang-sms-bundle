<?php

namespace JiguangSmsBundle\Request\Sign;

use JiguangSmsBundle\Entity\Sign;

class CreateSignRequest extends AbstractSignRequest
{
    private Sign $sign;

    public function getRequestPath(): string
    {
        return $this->getBaseUrl();
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
    }

    public function getRequestOptions(): ?array
    {
        $options = [
            'multipart' => [
                [
                    'name' => 'sign',
                    'contents' => $this->sign->getSign(),
                ],
                [
                    'name' => 'type',
                    'contents' => $this->sign->getType()->value,
                ],
            ],
        ];

        if ($this->sign->getRemark() !== null) {
            $options['multipart'][] = [
                'name' => 'remark',
                'contents' => $this->sign->getRemark(),
            ];
        }

        if ($this->sign->getImage0() !== null) {
            $options['multipart'][] = [
                'name' => 'image0',
                'contents' => fopen($this->sign->getImage0(), 'r'),
                'filename' => basename($this->sign->getImage0()),
            ];
        }

        if ($this->sign->getImage1() !== null) {
            $options['multipart'][] = [
                'name' => 'image1',
                'contents' => fopen($this->sign->getImage1(), 'r'),
                'filename' => basename($this->sign->getImage1()),
            ];
        }

        return $options;
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
