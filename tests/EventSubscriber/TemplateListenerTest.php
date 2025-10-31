<?php

namespace JiguangSmsBundle\Tests\EventSubscriber;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\EventSubscriber\TemplateListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(TemplateListener::class)]
#[RunTestsInSeparateProcesses]
final class TemplateListenerTest extends AbstractIntegrationTestCase
{
    private TemplateListener $listener;

    protected function onSetUp(): void
    {
        $this->listener = self::getService(TemplateListener::class);
    }

    public function testPostPersistWithNewTemplate(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        // 模拟新建状态：没有 tempId，不在同步状态
        $template->setSyncing(false);

        // 验证初始状态
        $this->assertFalse($template->isSyncing());
        $this->assertNull($template->getTempId());

        // 在测试环境中，不会调用外部 API，会直接设置测试用的 tempId

        $this->listener->postPersist($template);

        // 验证在测试环境下设置了测试用的 tempId
        $this->assertEquals(888888, $template->getTempId());
    }

    public function testPostPersistWithSyncingTemplate(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        // 模拟同步状态：正在同步中
        $template->setSyncing(true);

        // 验证同步状态
        $this->assertTrue($template->isSyncing());

        // 在测试环境中，不会调用外部 API

        $this->listener->postPersist($template);
    }

    public function testPostUpdateWithExistingTemplate(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        // 模拟已存在状态：有 tempId，不在同步状态
        $template->setTempId(123);
        $template->setSyncing(false);

        // 验证状态
        $this->assertFalse($template->isSyncing());
        $this->assertEquals(123, $template->getTempId());

        // 在测试环境中，不会调用外部 API

        $this->listener->postUpdate($template);
    }

    public function testPreRemoveWithExistingTemplate(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        // 模拟已存在状态：有 tempId，不在同步状态
        $template->setTempId(123);
        $template->setSyncing(false);

        // 验证状态
        $this->assertFalse($template->isSyncing());
        $this->assertEquals(123, $template->getTempId());

        // 在测试环境中，不会调用外部 API

        $this->listener->preRemove($template);
    }
}
