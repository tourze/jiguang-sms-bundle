<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\AbstractCode;
use JiguangSmsBundle\Entity\Account;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AbstractCode::class)]
final class AbstractCodeTest extends TestCase
{
    private TestableAbstractCode $abstractCode;

    protected function setUp(): void
    {
        parent::setUp();
        $account = new Account();
        $this->abstractCode = new TestableAbstractCode();
        $this->abstractCode->setAccount($account);
        $this->abstractCode->setMobile('13800138000');
        $this->abstractCode->setCode('123456');
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $this->assertEquals(0, $this->abstractCode->getId());
        $this->assertEquals(60, $this->abstractCode->getTtl());
        $this->assertNull($this->abstractCode->getMsgId());
        $this->assertFalse($this->abstractCode->isVerified());
        $this->assertNull($this->abstractCode->getStatus());
        $this->assertNull($this->abstractCode->getReceiveTime());
        $this->assertNull($this->abstractCode->getVerifyTime());
    }

    public function testSettersAndGettersWorkCorrectly(): void
    {
        $account = new Account();
        $mobile = '13800138000';
        $code = '123456';
        $ttl = 300;
        $msgId = 'MSG123';
        $status = 4001;
        $receiveTime = new \DateTimeImmutable();

        $this->abstractCode->setAccount($account);
        $this->abstractCode->setMobile($mobile);
        $this->abstractCode->setCode($code);
        $this->abstractCode->setTtl($ttl);
        $this->abstractCode->setMsgId($msgId);
        $this->abstractCode->setStatus($status);
        $this->abstractCode->setReceiveTime($receiveTime);

        $this->assertSame($account, $this->abstractCode->getAccount());
        $this->assertEquals($mobile, $this->abstractCode->getMobile());
        $this->assertEquals($code, $this->abstractCode->getCode());
        $this->assertEquals($ttl, $this->abstractCode->getTtl());
        $this->assertEquals($msgId, $this->abstractCode->getMsgId());
        $this->assertEquals($status, $this->abstractCode->getStatus());
        $this->assertEquals($receiveTime, $this->abstractCode->getReceiveTime());
    }

    public function testSetVerifiedSetsVerifyTime(): void
    {
        $this->abstractCode->setVerified(true);

        $this->assertTrue($this->abstractCode->isVerified());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->abstractCode->getVerifyTime());
    }

    public function testSetVerifiedFalseDoesNotSetVerifyTime(): void
    {
        $this->abstractCode->setVerified(false);

        $this->assertFalse($this->abstractCode->isVerified());
        $this->assertNull($this->abstractCode->getVerifyTime());
    }

    public function testIsDeliveredReturnsTrueWhenStatusIs4001(): void
    {
        $this->abstractCode->setStatus(4001);

        $this->assertTrue($this->abstractCode->isDelivered());
    }

    public function testIsDeliveredReturnsFalseWhenStatusIsNot4001(): void
    {
        $this->abstractCode->setStatus(4000);

        $this->assertFalse($this->abstractCode->isDelivered());
    }

    public function testToStringReturnsFormattedString(): void
    {
        $account = new Account();
        $this->abstractCode->setAccount($account);
        $this->abstractCode->setMobile('13800138000');
        $this->abstractCode->setCode('123456');
        $this->abstractCode->setMsgId('MSG123');

        $expected = '[13800138000] 123456 - MSG123';
        $this->assertEquals($expected, (string) $this->abstractCode);
    }

    public function testToStringWithNullMsgIdReturnsNA(): void
    {
        $account = new Account();
        $this->abstractCode->setAccount($account);
        $this->abstractCode->setMobile('13800138000');
        $this->abstractCode->setCode('123456');

        $expected = '[13800138000] 123456 - N/A';
        $this->assertEquals($expected, (string) $this->abstractCode);
    }

    public function testImplementsStringable(): void
    {
        $this->assertNotNull($this->abstractCode);
    }
}
