<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Entity\Sign;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function test_constructor_setsDefaultValues(): void
    {
        $message = new Message();

        $this->assertEquals(0, $message->getId());
        $this->assertNull($message->getMsgId());
        $this->assertNull($message->getStatus());
        $this->assertNull($message->getReceiveTime());
        $this->assertNull($message->getResponse());
    }

    public function test_settersAndGetters_workCorrectly(): void
    {
        $message = new Message();
        $account = new Account();
        $template = new Template();
        $sign = new Sign();
        $msgId = 'MSG123';
        $mobile = '13800138000';
        $status = 4001;

        $message->setAccount($account);
        $message->setTemplate($template);
        $message->setSign($sign);
        $message->setMsgId($msgId);
        $message->setMobile($mobile);
        $message->setStatus($status);

        $this->assertSame($account, $message->getAccount());
        $this->assertSame($template, $message->getTemplate());
        $this->assertSame($sign, $message->getSign());
        $this->assertEquals($msgId, $message->getMsgId());
        $this->assertEquals($mobile, $message->getMobile());
        $this->assertEquals($status, $message->getStatus());
    }

    public function test_isDelivered_withDeliveredStatus_returnsTrue(): void
    {
        $message = new Message();
        $message->setStatus(4001);

        $this->assertTrue($message->isDelivered());
    }

    public function test_isDelivered_withNonDeliveredStatus_returnsFalse(): void
    {
        $message = new Message();
        $message->setStatus(4002);

        $this->assertFalse($message->isDelivered());
    }

    public function test_toString_returnsFormattedString(): void
    {
        $template = new Template();
        $message = new Message();
        $message->setMobile('13800138000');
        $message->setTemplate($template);
        $message->setMsgId('MSG123');

        $result = (string) $message;
        $this->assertStringContainsString('13800138000', $result);
        $this->assertStringContainsString('MSG123', $result);
    }
}
