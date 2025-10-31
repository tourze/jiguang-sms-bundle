<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Entity\TextCode;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(TextCode::class)]
final class TextCodeTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new TextCode();
    }

    /** @return iterable<array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'mobile' => ['mobile', '13800138000'];
        yield 'code' => ['code', '123456'];
        yield 'ttl' => ['ttl', 300];
        yield 'msgId' => ['msgId', 'MSG123'];
        yield 'verified' => ['verified', true];
        yield 'status' => ['status', 4001];
        yield 'receiveTime' => ['receiveTime', new \DateTimeImmutable()];
        yield 'verifyTime' => ['verifyTime', new \DateTimeImmutable()];
        yield 'template' => ['template', new Template()];
        yield 'sign' => ['sign', new Sign()];
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $textCode = new TextCode();

        $this->assertEquals(0, $textCode->getId());
        $this->assertNull($textCode->getStatus());
        $this->assertFalse($textCode->isVerified());
        $this->assertNull($textCode->getTemplate());
        $this->assertNull($textCode->getSign());
    }

    public function testSettersAndGettersWorkCorrectly(): void
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

    public function testToStringReturnsFormattedString(): void
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

    public function testToStringWithNullMsgIdReturnsDefaultString(): void
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
