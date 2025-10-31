<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\VoiceCode;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(VoiceCode::class)]
final class VoiceCodeTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new VoiceCode();
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
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $voiceCode = new VoiceCode();

        $this->assertInstanceOf(VoiceCode::class, $voiceCode);
        $this->assertSame(60, $voiceCode->getTtl());
        $this->assertFalse($voiceCode->isVerified());
    }

    public function testImplementsStringable(): void
    {
        $voiceCode = new VoiceCode();
        $account = new Account();
        $voiceCode->setAccount($account);
        $voiceCode->setMobile('13800138000');
        $voiceCode->setCode('123456');

        $stringRepresentation = (string) $voiceCode;
        $this->assertNotEmpty($stringRepresentation);
    }
}
