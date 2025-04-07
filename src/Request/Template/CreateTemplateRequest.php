<?php

namespace JiguangSmsBundle\Request\Template;

use JiguangSmsBundle\Entity\Template;

class CreateTemplateRequest extends AbstractTemplateRequest
{
    private Template $template;

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
