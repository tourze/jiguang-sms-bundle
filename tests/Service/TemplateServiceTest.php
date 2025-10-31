<?php

namespace JiguangSmsBundle\Tests\Service;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Exception\InvalidTemplateStatusException;
use JiguangSmsBundle\Request\Template\CreateTemplateRequest;
use JiguangSmsBundle\Request\Template\DeleteTemplateRequest;
use JiguangSmsBundle\Request\Template\UpdateTemplateRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Service\TemplateService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TemplateService::class)]
final class TemplateServiceTest extends TestCase
{
    /** @var MockObject&JiguangSmsService */
    private JiguangSmsService $jiguangSmsService;

    private TemplateService $templateService;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用 createMock 进行 JiguangSmsService 的模拟，因为：
        // 1. 这是一个具体类，但需要隔离外部依赖（HTTP请求）
        // 2. 测试重点在于验证方法调用和参数传递，而不是实际的网络请求
        // 3. 使用 Mock 可以确保测试的可重复性和速度
        /** @var MockObject&JiguangSmsService $jiguangSmsService */
        $jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->jiguangSmsService = $jiguangSmsService;
        $this->templateService = new TemplateService($this->jiguangSmsService);
    }

    public function testConstructorSetsJiguangSmsService(): void
    {
        $this->assertNotNull($this->templateService);
    }

    public function testCreateRemoteTemplateCallsJiguangSmsService(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');

        // 此时 tempId 为 null，在调用后会被设置为 123
        $this->assertNull($template->getTempId());

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['temp_id' => 123])
        ;

        $this->templateService->createRemoteTemplate($template);

        // 验证 tempId 被设置了
        $this->assertEquals(123, $template->getTempId());
    }

    public function testUpdateRemoteTemplateCallsJiguangSmsService(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        $template->setTempId(123);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
        ;

        $this->templateService->updateRemoteTemplate($template);
    }

    public function testDeleteRemoteTemplateCallsJiguangSmsService(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        $template->setTempId(123);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
        ;

        $this->templateService->deleteRemoteTemplate($template);
    }

    public function testSyncTemplateStatusWithValidStatusSetsStatus(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        $template->setTempId(123);

        // 初始状态应该是 PENDING
        $this->assertEquals(TemplateStatusEnum::PENDING, $template->getStatus());

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['status' => 1])
        ;

        $this->templateService->syncTemplateStatus($template);

        // 验证状态被设置为 APPROVED
        $this->assertEquals(TemplateStatusEnum::APPROVED, $template->getStatus());
    }

    public function testSyncTemplateStatusWithInvalidStatusThrowsException(): void
    {
        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Template 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板');
        $template->setTempId(123);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['status' => 999])
        ;

        $this->expectException(InvalidTemplateStatusException::class);

        $this->templateService->syncTemplateStatus($template);
    }

    public function testCreateRemoteTemplateSuccess(): void
    {
        // 准备测试数据
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板内容');

        // 模拟JiguangSmsService.request方法返回结果
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with(self::callback(function ($request) use ($account, $template) {
                return $request instanceof CreateTemplateRequest
                    && $request->getAccount() === $account
                    && $request->getTemplate() === $template;
            }))
            ->willReturn(['temp_id' => 123])
        ;

        // 执行测试
        $this->templateService->createRemoteTemplate($template);

        // 验证模板ID被正确设置
        $this->assertEquals(123, $template->getTempId());
    }

    public function testUpdateRemoteTemplateSuccess(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('测试模板内容更新');
        $template->setTempId(123);

        // 模拟JiguangSmsService.request方法
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with(self::callback(function ($request) use ($account, $template) {
                return $request instanceof UpdateTemplateRequest
                    && $request->getAccount() === $account
                    && $request->getTemplate() === $template;
            }))
            ->willReturn([])
        ;

        // 执行测试
        $this->templateService->updateRemoteTemplate($template);
    }

    public function testDeleteRemoteTemplateSuccess(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);
        $template->setTempId(123);

        // 模拟JiguangSmsService.request方法
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with(self::callback(function ($request) use ($account, $template) {
                return $request instanceof DeleteTemplateRequest
                    && $request->getAccount() === $account
                    && $request->getTemplate() === $template;
            }))
            ->willReturn([])
        ;

        // 执行测试
        $this->templateService->deleteRemoteTemplate($template);
    }

    public function testSyncTemplateStatusPending(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 待审核状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 0,  // 待审核
            ])
        ;

        // 执行测试
        $this->templateService->syncTemplateStatus($template);

        // 验证状态被正确设置
        $this->assertEquals(TemplateStatusEnum::PENDING, $template->getStatus());
    }

    public function testSyncTemplateStatusApproved(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已通过状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 1,  // 已通过
            ])
        ;

        // 执行测试
        $this->templateService->syncTemplateStatus($template);

        // 验证状态被正确设置
        $this->assertEquals(TemplateStatusEnum::APPROVED, $template->getStatus());
    }

    public function testSyncTemplateStatusRejected(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已拒绝状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 2,  // Now this becomes REJECTED
            ])
        ;

        // 执行测试
        $this->templateService->syncTemplateStatus($template);

        // 验证状态被正确设置
        $this->assertEquals(TemplateStatusEnum::REJECTED, $template->getStatus());
    }

    public function testSyncTemplateStatusUnknownStatus(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 未知状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 99,  // 未知状态
            ])
        ;

        // 期望抛出异常
        $this->expectException(InvalidTemplateStatusException::class);
        $this->expectExceptionMessage('无效的模板状态: 99');

        // 执行测试
        $this->templateService->syncTemplateStatus($template);
    }
}
