<?php

namespace JiguangSmsBundle\Request\Template;

use JiguangSmsBundle\Entity\Template;

class DeleteTemplateRequest extends AbstractTemplateRequest
{
    private Template $template;

    public function getRequestPath(): string
    {
        return sprintf('%s/%d', $this->getBaseUrl(), $this->template->getTempId());
    }

    public function getRequestMethod(): ?string
    {
        return 'DELETE';
    }

    public function getRequestOptions(): ?array
    {
        return null;
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
