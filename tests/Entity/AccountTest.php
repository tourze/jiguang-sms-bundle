<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Account::class)]
final class AccountTest extends AbstractEntityTestCase
{
    protected function setUp(): void
    {
        // 空实现，因为此测试不需要特殊的设置
    }

    protected function createEntity(): object
    {
        return new Account();
    }

    /** @return iterable<array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'title' => ['title', '测试账号'];
        yield 'appKey' => ['appKey', 'test_app_key'];
        yield 'masterSecret' => ['masterSecret', 'test_master_secret'];
        yield 'valid' => ['valid', true];
        yield 'createdBy' => ['createdBy', 'creator'];
        yield 'updatedBy' => ['updatedBy', 'updater'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable()];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable()];
    }

    public function testGetterAndSetterMethods(): void
    {
        $account = new Account();

        // 测试标题
        $account->setTitle('测试账号');
        $this->assertEquals('测试账号', $account->getTitle());

        // 测试AppKey
        $account->setAppKey('test_app_key');
        $this->assertEquals('test_app_key', $account->getAppKey());

        // 测试MasterSecret
        $account->setMasterSecret('test_master_secret');
        $this->assertEquals('test_master_secret', $account->getMasterSecret());

        // 测试有效标志
        $account->setValid(true);
        $this->assertTrue($account->isValid());

        // 测试创建者和更新者
        $account->setCreatedBy('creator');
        $this->assertEquals('creator', $account->getCreatedBy());

        $account->setUpdatedBy('updater');
        $this->assertEquals('updater', $account->getUpdatedBy());

        // 测试创建时间和更新时间
        $now = new \DateTimeImmutable();
        $account->setCreateTime($now);
        $this->assertSame($now, $account->getCreateTime());

        $updateTime = new \DateTimeImmutable('+1 hour');
        $account->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $account->getUpdateTime());
    }

    public function testDefaultValues(): void
    {
        $account = new Account();

        $this->assertEquals(0, $account->getId());
        $this->assertNull($account->getTitle());
        $this->assertNull($account->getAppKey());
        $this->assertNull($account->getMasterSecret());
        $this->assertFalse($account->isValid());
        $this->assertNull($account->getCreatedBy());
        $this->assertNull($account->getUpdatedBy());
        $this->assertNull($account->getCreateTime());
        $this->assertNull($account->getUpdateTime());
    }
}
