<?php

namespace JiguangSmsBundle\Request\Sign;

use JiguangSmsBundle\Entity\Sign;

class UpdateSignRequest extends AbstractSignRequest
{
    private Sign $sign;

    public function getRequestPath(): string
    {
        return sprintf('%s/%d', $this->getBaseUrl(), $this->sign->getSignId());
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
    }

    /**
     * @return array<string, mixed>|null
     */
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

        if (null !== $this->sign->getRemark()) {
            $options['multipart'][] = [
                'name' => 'remark',
                'contents' => $this->sign->getRemark(),
            ];
        }

        if (null !== $this->sign->getImage0()) {
            $options['multipart'][] = [
                'name' => 'image0',
                'contents' => fopen($this->sign->getImage0(), 'r'),
                'filename' => basename($this->sign->getImage0()),
            ];
        }

        if (null !== $this->sign->getImage1()) {
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

    public function setSign(Sign $sign): void
    {
        $this->sign = $sign;
    }
}
