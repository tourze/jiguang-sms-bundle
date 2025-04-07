<?php

namespace JiguangSmsBundle\Request\Template;

use JiguangSmsBundle\Entity\Template;

class UpdateTemplateRequest extends AbstractTemplateRequest
{
    private Template $template;

    public function getRequestPath(): string
    {
        return sprintf('%s/%d', $this->getBaseUrl(), $this->template->getTempId());
    }

    public function getRequestMethod(): ?string
    {
        return 'PUT';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'template' => $this->template->getTemplate(),
                'type' => $this->template->getType()->value,
                'ttl' => $this->template->getTtl(),
                'remark' => $this->template->getRemark(),
            ],
        ];
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): self
    {
        $this->template = $template;
        return $this;
    }
}
