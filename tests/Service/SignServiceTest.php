<?php

namespace JiguangSmsBundle\Tests\Service;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Exception\InvalidSignStatusException;
use JiguangSmsBundle\Request\Sign\CreateSignRequest;
use JiguangSmsBundle\Request\Sign\DeleteSignRequest;
use JiguangSmsBundle\Request\Sign\UpdateSignRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Service\SignService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(SignService::class)]
#[RunTestsInSeparateProcesses]
final class SignServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 空实现，测试不需要特殊初始化
    }

    /**
     * @return array{MockObject&JiguangSmsService, SignService}
     */
    private function createSignService(): array
    {
        // 使用 createMock 进行 JiguangSmsService 的模拟，因为：
        // 1. 这是一个具体类，但需要隔离外部依赖（HTTP请求）
        // 2. 测试重点在于验证方法调用和参数传递，而不是实际的网络请求
        // 3. 使用 Mock 可以确保测试的可重复性和速度
        /** @var MockObject&JiguangSmsService $jiguangSmsService */
        $jiguangSmsService = $this->createMock(JiguangSmsService::class);

        // 直接实例化 SignService，避免服务容器的限制
        // 由于服务容器限制无法替换已初始化的服务，这是当前最佳解决方案
        // @phpstan-ignore integrationTest.noDirectInstantiationOfCoveredClass
        $signService = new SignService($jiguangSmsService);

        return [$jiguangSmsService, $signService];
    }

    public function testConstructorSetsJiguangSmsService(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();
        $this->assertInstanceOf(SignService::class, $signService);
    }

    public function testCreateRemoteSignCallsJiguangSmsService(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');

        // 此时 signId 为 null，在调用后会被设置为 123
        $this->assertNull($sign->getSignId());

        $jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['sign_id' => 123])
        ;

        $signService->createRemoteSign($sign);

        // 验证 signId 被设置了
        $this->assertEquals(123, $sign->getSignId());
    }

    public function testUpdateRemoteSignCallsJiguangSmsService(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setSignId(123);

        $jiguangSmsService->expects($this->once())
            ->method('request')
        ;

        $signService->updateRemoteSign($sign);
    }

    public function testDeleteRemoteSignCallsJiguangSmsService(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setSignId(123);

        $jiguangSmsService->expects($this->once())
            ->method('request')
        ;

        $signService->deleteRemoteSign($sign);
    }

    public function testSyncSignStatusWithValidStatusSetsStatus(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setSignId(123);

        // 初始状态应该是 PENDING
        $this->assertEquals(SignStatusEnum::PENDING, $sign->getStatus());

        $jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['status' => 1])
        ;

        $signService->syncSignStatus($sign);

        // 验证状态被设置为 APPROVED
        $this->assertEquals(SignStatusEnum::APPROVED, $sign->getStatus());
    }

    public function testSyncSignStatusWithInvalidStatusThrowsException(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 使用真实对象而不是 Mock，因为：
        // 1. PHPStan 规范要求 createMock() 只能用于抽象类和接口
        // 2. Sign 和 Account 是具体实体类，不符合 Mock 要求
        // 3. 使用真实对象可以更版地测试实际行为
        $account = new Account();
        $account->setTitle('Test Account');
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setSignId(123);

        $jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['status' => 999])
        ;

        $this->expectException(InvalidSignStatusException::class);

        $signService->syncSignStatus($sign);
    }

    public function testCreateRemoteSignSuccess(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');

        // 模拟JiguangSmsService.request方法返回结果
        $jiguangSmsService->expects($this->once())
            ->method('request')
            ->with(self::callback(function ($request) use ($account, $sign) {
                return $request instanceof CreateSignRequest
                    && $request->getAccount() === $account
                    && $request->getSign() === $sign;
            }))
            ->willReturn(['sign_id' => 123])
        ;

        // 执行测试
        $signService->createRemoteSign($sign);

        // 验证签名ID被正确设置
        $this->assertEquals(123, $sign->getSignId());
    }

    public function testUpdateRemoteSignSuccess(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setSignId(123);

        // 模拟JiguangSmsService.request方法
        $jiguangSmsService->expects($this->once())
            ->method('request')
            ->with(self::callback(function ($request) use ($account, $sign) {
                return $request instanceof UpdateSignRequest
                    && $request->getAccount() === $account
                    && $request->getSign() === $sign;
            }))
            ->willReturn([])
        ;

        // 执行测试
        $signService->updateRemoteSign($sign);
    }

    public function testDeleteRemoteSignSuccess(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSignId(123);

        // 模拟JiguangSmsService.request方法
        $jiguangSmsService->expects($this->once())
            ->method('request')
            ->with(self::callback(function ($request) use ($account, $sign) {
                return $request instanceof DeleteSignRequest
                    && $request->getAccount() === $account
                    && $request->getSign() === $sign;
            }))
            ->willReturn([])
        ;

        // 执行测试
        $signService->deleteRemoteSign($sign);
    }

    public function testSyncSignStatusPending(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 待审核状态
        $jiguangSmsService->method('request')
            ->willReturn([
                'status' => 0,  // 待审核
                'is_default' => false,
                'use_status' => false,
            ])
        ;

        // 执行测试
        $signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::PENDING, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
    }

    public function testSyncSignStatusApproved(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已通过状态
        $jiguangSmsService->method('request')
            ->willReturn([
                'status' => 1,  // The status is 1 (APPROVED)
                'is_default' => true,
                'use_status' => true,
            ])
        ;

        // 执行测试
        $signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::APPROVED, $sign->getStatus());
        $this->assertTrue($sign->isDefault());
        $this->assertTrue($sign->isUseStatus());
    }

    public function testSyncSignStatusRejected(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已拒绝状态
        $jiguangSmsService->method('request')
            ->willReturn([
                'status' => 2,  // The status is 2 (REJECTED)
                'is_default' => false,
                'use_status' => false,
            ])
        ;

        // 执行测试
        $signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::REJECTED, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
    }

    public function testSyncSignStatusDeleted(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 已删除状态
        $jiguangSmsService->method('request')
            ->willReturn([
                'status' => 3,  // The status is 3 (DELETED)
                'is_default' => false,
                'use_status' => false,
            ])
        ;

        // 执行测试
        $signService->syncSignStatus($sign);

        // 验证状态和其他属性被正确设置
        $this->assertEquals(SignStatusEnum::DELETED, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
    }

    public function testSyncSignStatusUnknownStatus(): void
    {
        [$jiguangSmsService, $signService] = $this->createSignService();

        // 准备测试数据
        $account = new Account();
        $sign = new Sign();
        $sign->setAccount($account);

        // 模拟JiguangSmsService.request方法 - 未知状态
        $jiguangSmsService->method('request')
            ->willReturn([
                'status' => 99,  // 未知状态
                'is_default' => false,
                'use_status' => false,
            ])
        ;

        // 期望抛出异常
        $this->expectException(InvalidSignStatusException::class);
        $this->expectExceptionMessage('无效的签名状态: 99');

        // 执行测试
        $signService->syncSignStatus($sign);
    }
}
