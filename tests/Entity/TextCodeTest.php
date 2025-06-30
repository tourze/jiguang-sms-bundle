<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\TextCode;
use PHPUnit\Framework\TestCase;

class TextCodeTest extends TestCase
{
    public function test_constructor_setsDefaultValues(): void
    {
        $textCode = new TextCode();

        $this->assertEquals(0, $textCode->getId());
        $this->assertNull($textCode->getStatus());
        $this->assertFalse($textCode->isVerified());
        $this->assertNull($textCode->getTemplate());
        $this->assertNull($textCode->getSign());
    }

    public function test_settersAndGetters_workCorrectly(): void
    {
        $textCode = new TextCode();
        $account = new Account();
        $mobile = '13800138000';
        $code = '123456';
        $status = 4001;

        $textCode->setAccount($account);
        $textCode->setMobile($mobile);
        $textCode->setCode($code);
        $textCode->setStatus($status);
        $textCode->setVerified(true);

        $this->assertSame($account, $textCode->getAccount());
        $this->assertEquals($mobile, $textCode->getMobile());
        $this->assertEquals($code, $textCode->getCode());
        $this->assertEquals($status, $textCode->getStatus());
        $this->assertTrue($textCode->isVerified());
        $this->assertTrue($textCode->isDelivered());
    }

    public function test_toString_returnsFormattedString(): void
    {
        $textCode = new TextCode();
        $account = new Account();
        $textCode->setAccount($account);
        $textCode->setMobile('13800138000');
        $textCode->setCode('123456');
        $textCode->setMsgId('MSG123');

        $expected = '[13800138000] 123456 - MSG123';
        $this->assertEquals($expected, (string) $textCode);
    }

    public function test_toString_withNullMsgId_returnsDefaultString(): void
    {
        $textCode = new TextCode();
        $account = new Account();
        $textCode->setAccount($account);
        $textCode->setMobile('13800138000');
        $textCode->setCode('123456');

        $expected = '[13800138000] 123456 - N/A';
        $this->assertEquals($expected, (string) $textCode);
    }
} 