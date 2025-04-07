<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\TextCodeRepository;

#[ORM\Entity(repositoryClass: TextCodeRepository::class)]
#[ORM\Table(name: 'jg_sms_text_code')]
class TextCode extends AbstractCode
{
    #[ORM\ManyToOne(targetEntity: Template::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Template $template = null;

    #[ORM\ManyToOne(targetEntity: Sign::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Sign $sign = null;

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function getSign(): ?Sign
    {
        return $this->sign;
    }

    public function setSign(?Sign $sign): self
    {
        $this->sign = $sign;
        return $this;
    }
}
