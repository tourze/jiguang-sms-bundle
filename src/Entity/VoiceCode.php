<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\VoiceCodeRepository;

#[ORM\Entity(repositoryClass: VoiceCodeRepository::class)]
#[ORM\Table(name: 'jg_sms_voice_code', options: ['comment' => '语音验证码'])]
class VoiceCode extends AbstractCode
{
}
