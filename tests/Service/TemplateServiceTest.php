<?php

namespace JiguangSmsBundle\Tests\Service;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Request\Template\CreateTemplateRequest;
use JiguangSmsBundle\Request\Template\DeleteTemplateRequest;
use JiguangSmsBundle\Request\Template\UpdateTemplateRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Service\TemplateService;
use PHPUnit\Framework\TestCase;

class TemplateServiceTest extends TestCase
{
    private JiguangSmsService $jiguangSmsService;
    private TemplateService $templateService;

    protected function setUp(): void
    {
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->templateService = new TemplateService($this->jiguangSmsService);
    }

    public function testCreateRemoteTemplate_success(): void
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
            ->with($this->callback(function ($request) use ($account, $template) {
                return $request instanceof CreateTemplateRequest
                    && $request->getAccount() === $account
                    && $request->getTemplate() === $template;
            }))
            ->willReturn(['temp_id' => 123]);

        // 执行测试
        $this->templateService->createRemoteTemplate($template);

        // 验证模板ID被正确设置
        $this->assertEquals(123, $template->getTempId());
    }

    public function testUpdateRemoteTemplate_success(): void
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
            ->with($this->callback(function ($request) use ($account, $template) {
                return $request instanceof UpdateTemplateRequest
                    && $request->getAccount() === $account
                    && $request->getTemplate() === $template;
            }))
            ->willReturn([]);

        // 执行测试
        $this->templateService->updateRemoteTemplate($template);
    }

    public function testDeleteRemoteTemplate_success(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);
        $template->setTempId(123);

        // 模拟JiguangSmsService.request方法
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with($this->callback(function ($request) use ($account, $template) {
                return $request instanceof DeleteTemplateRequest
                    && $request->getAccount() === $account
                    && $request->getTemplate() === $template;
            }))
            ->willReturn([]);

        // 执行测试
        $this->templateService->deleteRemoteTemplate($template);
    }

    public function testSyncTemplateStatus_pending(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 待审核状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 0  // 待审核
            ]);

        // 执行测试
        $this->templateService->syncTemplateStatus($template);

        // 验证状态被正确设置
        $this->assertEquals(TemplateStatusEnum::PENDING, $template->getStatus());
    }

    public function testSyncTemplateStatus_approved(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已通过状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 1  // 已通过
            ]);

        // 执行测试
        $this->templateService->syncTemplateStatus($template);

        // 验证状态被正确设置
        $this->assertEquals(TemplateStatusEnum::APPROVED, $template->getStatus());
    }

    public function testSyncTemplateStatus_rejected(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已拒绝状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 2  // Now this becomes REJECTED
            ]);

        // 执行测试
        $this->templateService->syncTemplateStatus($template);

        // 验证状态被正确设置
        $this->assertEquals(TemplateStatusEnum::REJECTED, $template->getStatus());
    }

    public function testSyncTemplateStatus_unknownStatus(): void
    {
        // 准备测试数据
        $account = new Account();
        $template = new Template();
        $template->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 未知状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 99  // 未知状态
            ]);

        // 期望抛出异常
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unknown template status');

        // 执行测试
        $this->templateService->syncTemplateStatus($template);
    }
}
