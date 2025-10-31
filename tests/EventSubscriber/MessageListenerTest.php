<?php

namespace JiguangSmsBundle\Tests\EventSubscriber;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\EventSubscriber\MessageListener;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(MessageListener::class)]
final class MessageListenerTest extends TestCase
{
    /** @var MockObject&JiguangSmsService */
    private JiguangSmsService $jiguangSmsService;

    private MessageListener $listener;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var MockObject&JiguangSmsService $jiguangSmsService */
        $jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->jiguangSmsService = $jiguangSmsService;
        $this->listener = new MessageListener($this->jiguangSmsService, 'test');
    }

    public function testPrePersist(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Message 和 Template 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        $template->setTempId(123);

        $message = new Message();
        $message->setAccount($account);
        $message->setTemplate($template);
        $message->setMobile('13800138000');
        // 模拟新建状态：没有 msgId

        // 验证初始状态
        $this->assertNull($message->getMsgId());
        $this->assertEquals($template, $message->getTemplate());
        $this->assertEquals(123, $template->getTempId());

        // 在测试环境中，不会调用外部 API，而是直接设置测试用的 msgId
        $this->jiguangSmsService->expects($this->never())
            ->method('request')
        ;

        $this->listener->prePersist($message);

        // 验证结果状态：在测试环境中会设置测试用的 msgId 和 response
        $msgId = $message->getMsgId();
        $this->assertNotNull($msgId);
        $this->assertStringStartsWith('test_message_id_', $msgId);
        $this->assertIsArray($message->getResponse());
        $this->assertArrayHasKey('test', $message->getResponse());
    }

    public function testPrePersistWithExistingMsgId(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Message 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        $template->setTempId(123);

        $message = new Message();
        $message->setAccount($account);
        $message->setTemplate($template);
        $message->setMobile('13800138000');
        // 模拟已存在状态：有 msgId
        $message->setMsgId('existing-msg-id');

        // 验证初始状态
        $this->assertEquals('existing-msg-id', $message->getMsgId());

        $this->jiguangSmsService->expects($this->never())
            ->method('request')
        ;

        $this->listener->prePersist($message);
    }
}
