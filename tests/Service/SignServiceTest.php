<?php

namespace JiguangSmsBundle\Tests\Service;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Request\Sign\CreateSignRequest;
use JiguangSmsBundle\Request\Sign\DeleteSignRequest;
use JiguangSmsBundle\Request\Sign\UpdateSignRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Service\SignService;
use PHPUnit\Framework\TestCase;

class SignServiceTest extends TestCase
{
    private JiguangSmsService $jiguangSmsService;
    private SignService $signService;

    protected function setUp(): void
    {
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->signService = new SignService($this->jiguangSmsService);
    }

    public function testCreateRemoteSign_success(): void
    {
        // 准备测试数据
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');

        // 模拟JiguangSmsService.request方法返回结果
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with($this->callback(function ($request) use ($account, $sign) {
                return $request instanceof CreateSignRequest
                    && $request->getAccount() === $account
                    && $request->getSign() === $sign;
            }))
            ->willReturn(['sign_id' => 123]);

        // 执行测试
        $this->signService->createRemoteSign($sign);

        // 验证签名ID被正确设置
        $this->assertEquals(123, $sign->getSignId());
    }

    public function testUpdateRemoteSign_success(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setSignId(123);

        // 模拟JiguangSmsService.request方法
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with($this->callback(function ($request) use ($account, $sign) {
                return $request instanceof UpdateSignRequest
                    && $request->getAccount() === $account
                    && $request->getSign() === $sign;
            }))
            ->willReturn([]);

        // 执行测试
        $this->signService->updateRemoteSign($sign);
    }

    public function testDeleteRemoteSign_success(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSignId(123);

        // 模拟JiguangSmsService.request方法
        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->with($this->callback(function ($request) use ($account, $sign) {
                return $request instanceof DeleteSignRequest
                    && $request->getAccount() === $account
                    && $request->getSign() === $sign;
            }))
            ->willReturn([]);

        // 执行测试
        $this->signService->deleteRemoteSign($sign);
    }

    public function testSyncSignStatus_pending(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 待审核状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 0,  // 待审核
                'is_default' => false,
                'use_status' => false
            ]);

        // 执行测试
        $this->signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::PENDING, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
    }

    public function testSyncSignStatus_approved(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已通过状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 1,  // The status is 1 (APPROVED)
                'is_default' => true,
                'use_status' => true
            ]);

        // 执行测试
        $this->signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::APPROVED, $sign->getStatus());
        $this->assertTrue($sign->isDefault());
        $this->assertTrue($sign->isUseStatus());
    }

    public function testSyncSignStatus_rejected(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已拒绝状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 2,  // The status is 2 (REJECTED)
                'is_default' => false,
                'use_status' => false
            ]);

        // 执行测试
        $this->signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::REJECTED, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
    }

    public function testSyncSignStatus_deleted(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已删除状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 3,  // The status is 3 (DELETED)
                'is_default' => false,
                'use_status' => false
            ]);

        // 执行测试
        $this->signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::DELETED, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
    }

    public function testSyncSignStatus_unknownStatus(): void
    {
        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 未知状态
        $this->jiguangSmsService->method('request')
            ->willReturn([
                'status' => 99,  // 未知状态
                'is_default' => false,
                'use_status' => false
            ]);

        // 期望抛出异常
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unknown sign status');

        // 执行测试
        $this->signService->syncSignStatus($sign);
    }
}
