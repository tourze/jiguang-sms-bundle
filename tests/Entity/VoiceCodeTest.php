<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\VoiceCode;
use PHPUnit\Framework\TestCase;

class VoiceCodeTest extends TestCase
{
    public function test_constructor_setsDefaultValues(): void
    {
        $voiceCode = new VoiceCode();

        $this->assertNotNull($voiceCode);
        $this->assertInstanceOf(VoiceCode::class, $voiceCode);
    }

    public function test_implementsStringable(): void
    {
        $voiceCode = new VoiceCode();
        $account = new Account();
        $voiceCode->setAccount($account);
        $voiceCode->setMobile('13800138000');
        $voiceCode->setCode('123456');
        
        $this->assertInstanceOf(\Stringable::class, $voiceCode);
        $stringRepresentation = (string) $voiceCode;
        $this->assertNotEmpty($stringRepresentation);
    }
} 