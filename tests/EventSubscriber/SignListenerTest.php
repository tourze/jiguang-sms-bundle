<?php

namespace JiguangSmsBundle\Tests\EventSubscriber;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\EventSubscriber\SignListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(SignListener::class)]
#[RunTestsInSeparateProcesses]
final class SignListenerTest extends AbstractIntegrationTestCase
{
    private SignListener $listener;

    protected function onSetUp(): void
    {
        $this->listener = self::getService(SignListener::class);
    }

    public function testPostPersistWithNewSign(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        // 模拟新建状态：没有 signId，不在同步状态
        $sign->setSyncing(false);

        // 验证初始状态
        $this->assertFalse($sign->isSyncing());
        $this->assertNull($sign->getSignId());

        // 在测试环境中，不会调用外部 API，会直接设置测试用的 signId

        $this->listener->postPersist($sign);

        // 验证在测试环境下设置了测试用的 signId
        $this->assertEquals(999999, $sign->getSignId());
    }

    public function testPostPersistWithSyncingSign(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        // 模拟同步状态：正在同步中
        $sign->setSyncing(true);

        // 验证同步状态
        $this->assertTrue($sign->isSyncing());

        // 在测试环境中，不会调用外部 API

        $this->listener->postPersist($sign);
    }

    public function testPostUpdateWithExistingSign(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        // 模拟已存在状态：有 signId，不在同步状态
        $sign->setSignId(123);
        $sign->setSyncing(false);

        // 验证状态
        $this->assertFalse($sign->isSyncing());
        $this->assertEquals(123, $sign->getSignId());

        // 在测试环境中，不会调用外部 API

        $this->listener->postUpdate($sign);
    }

    public function testPreRemoveWithExistingSign(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        // 模拟已存在状态：有 signId，不在同步状态
        $sign->setSignId(123);
        $sign->setSyncing(false);

        // 验证状态
        $this->assertFalse($sign->isSyncing());
        $this->assertEquals(123, $sign->getSignId());

        // 在测试环境中，不会调用外部 API

        $this->listener->preRemove($sign);
    }
}
