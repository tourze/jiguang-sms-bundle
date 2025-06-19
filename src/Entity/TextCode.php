<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\TextCodeRepository;

#[ORM\Entity(repositoryClass: TextCodeRepository::class)]
#[ORM\Table(name: 'jg_sms_text_code', options: ['comment' => '短信验证码'])]
class TextCode extends AbstractCode
{
    #[ORM\ManyToOne(targetEntity: Template::class)]
    #[ORM\JoinColumn(nullable: true, options: ['comment' => '短信模板'])]
    private ?Template $template = null;

    #[ORM\ManyToOne(targetEntity: Sign::class)]
    #[ORM\JoinColumn(nullable: true, options: ['comment' => '短信签名'])]
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
