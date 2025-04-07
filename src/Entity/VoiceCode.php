<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\VoiceCodeRepository;

#[ORM\Entity(repositoryClass: VoiceCodeRepository::class)]
#[ORM\Table(name: 'jg_sms_voice_code')]
class VoiceCode extends AbstractCode
{
}
