<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\AccountBalance;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(AccountBalance::class)]
final class AccountBalanceTest extends AbstractEntityTestCase
{
    protected function setUp(): void
    {
        // 空实现，因为 AccountBalance 测试不需要特殊的设置
    }

    protected function createEntity(): object
    {
        return new AccountBalance();
    }

    /** @return iterable<array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'balance' => ['balance', 100];
        yield 'voice' => ['voice', 50];
        yield 'industry' => ['industry', 200];
        yield 'market' => ['market', 300];
        yield 'account' => ['account', new Account()];
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $balance = new AccountBalance();

        $this->assertEquals(0, $balance->getId());
        $this->assertNull($balance->getBalance());
        $this->assertNull($balance->getVoice());
        $this->assertNull($balance->getIndustry());
        $this->assertNull($balance->getMarket());
    }

    public function testSettersAndGettersWorkCorrectly(): void
    {
        $balance = new AccountBalance();
        $account = new Account();
        $balanceValue = 100;
        $voiceValue = 50;
        $industryValue = 200;
        $marketValue = 300;

        $balance->setAccount($account);
        $balance->setBalance($balanceValue);
        $balance->setVoice($voiceValue);
        $balance->setIndustry($industryValue);
        $balance->setMarket($marketValue);

        $this->assertSame($account, $balance->getAccount());
        $this->assertEquals($balanceValue, $balance->getBalance());
        $this->assertEquals($voiceValue, $balance->getVoice());
        $this->assertEquals($industryValue, $balance->getIndustry());
        $this->assertEquals($marketValue, $balance->getMarket());
    }

    public function testToStringReturnsFormattedString(): void
    {
        $account = new Account();
        $balance = new AccountBalance();
        $balance->setAccount($account);
        $balance->setBalance(100);
        $balance->setVoice(50);

        $result = (string) $balance;
        $this->assertStringContainsString('余量:', $result);
        $this->assertStringContainsString('全类型=100', $result);
        $this->assertStringContainsString('语音=50', $result);
    }
}
