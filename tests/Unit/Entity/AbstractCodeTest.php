<?php

namespace JiguangSmsBundle\Tests\Unit\Entity;

use JiguangSmsBundle\Entity\AbstractCode;
use JiguangSmsBundle\Entity\Account;
use PHPUnit\Framework\TestCase;

class AbstractCodeTest extends TestCase
{
    private TestableAbstractCode $abstractCode;

    protected function setUp(): void
    {
        $this->abstractCode = new TestableAbstractCode();
    }

    public function test_constructor_setsDefaultValues(): void
    {
        $this->assertEquals(0, $this->abstractCode->getId());
        $this->assertEquals(60, $this->abstractCode->getTtl());
        $this->assertNull($this->abstractCode->getMsgId());
        $this->assertFalse($this->abstractCode->isVerified());
        $this->assertNull($this->abstractCode->getStatus());
        $this->assertNull($this->abstractCode->getReceiveTime());
        $this->assertNull($this->abstractCode->getVerifyTime());
    }

    public function test_settersAndGetters_workCorrectly(): void
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

    public function test_setVerified_setsVerifyTime(): void
    {
        $this->abstractCode->setVerified(true);

        $this->assertTrue($this->abstractCode->isVerified());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->abstractCode->getVerifyTime());
    }

    public function test_setVerified_false_doesNotSetVerifyTime(): void
    {
        $this->abstractCode->setVerified(false);

        $this->assertFalse($this->abstractCode->isVerified());
        $this->assertNull($this->abstractCode->getVerifyTime());
    }

    public function test_isDelivered_returnsTrue_whenStatusIs4001(): void
    {
        $this->abstractCode->setStatus(4001);

        $this->assertTrue($this->abstractCode->isDelivered());
    }

    public function test_isDelivered_returnsFalse_whenStatusIsNot4001(): void
    {
        $this->abstractCode->setStatus(4000);

        $this->assertFalse($this->abstractCode->isDelivered());
    }

    public function test_toString_returnsFormattedString(): void
    {
        $account = new Account();
        $this->abstractCode->setAccount($account);
        $this->abstractCode->setMobile('13800138000');
        $this->abstractCode->setCode('123456');
        $this->abstractCode->setMsgId('MSG123');

        $expected = '[13800138000] 123456 - MSG123';
        $this->assertEquals($expected, (string) $this->abstractCode);
    }

    public function test_toString_withNullMsgId_returnsNA(): void
    {
        $account = new Account();
        $this->abstractCode->setAccount($account);
        $this->abstractCode->setMobile('13800138000');
        $this->abstractCode->setCode('123456');

        $expected = '[13800138000] 123456 - N/A';
        $this->assertEquals($expected, (string) $this->abstractCode);
    }

    public function test_implementsStringable(): void
    {
        $this->assertInstanceOf(\Stringable::class, $this->abstractCode);
    }
}

class TestableAbstractCode extends AbstractCode
{
    // No additional implementation needed for testing
}