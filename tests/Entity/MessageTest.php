<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Message::class)]
final class MessageTest extends AbstractEntityTestCase
{
    protected function setUp(): void
    {
        // 空实现，因为此测试不需要特殊的设置
    }

    protected function createEntity(): object
    {
        return new Message();
    }

    /** @return iterable<array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'msgId' => ['msgId', 'MSG123'];
        yield 'mobile' => ['mobile', '13800138000'];
        yield 'status' => ['status', 4001];
        yield 'receiveTime' => ['receiveTime', new \DateTimeImmutable()];
        yield 'response' => ['response', ['key' => 'value']];
        yield 'account' => ['account', new Account()];
        yield 'template' => ['template', new Template()];
        yield 'sign' => ['sign', new Sign()];
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $message = new Message();

        $this->assertEquals(0, $message->getId());
        $this->assertNull($message->getMsgId());
        $this->assertNull($message->getStatus());
        $this->assertNull($message->getReceiveTime());
        $this->assertNull($message->getResponse());
    }

    public function testSettersAndGettersWorkCorrectly(): void
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

    public function testIsDeliveredWithDeliveredStatusReturnsTrue(): void
    {
        $message = new Message();
        $message->setStatus(4001);

        $this->assertTrue($message->isDelivered());
    }

    public function testIsDeliveredWithNonDeliveredStatusReturnsFalse(): void
    {
        $message = new Message();
        $message->setStatus(4002);

        $this->assertFalse($message->isDelivered());
    }

    public function testToStringReturnsFormattedString(): void
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
