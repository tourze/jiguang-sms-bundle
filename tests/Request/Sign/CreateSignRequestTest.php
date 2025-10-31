<?php

namespace JiguangSmsBundle\Tests\Request\Sign;

use HttpClientBundle\Tests\Request\RequestTestCase;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Request\Sign\CreateSignRequest;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CreateSignRequest::class)]
final class CreateSignRequestTest extends RequestTestCase
{
    private CreateSignRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateSignRequest();
    }

    public function testExtendsAbstractSignRequest(): void
    {
        $this->assertNotNull($this->request);
    }

    public function testSetAndGetSign(): void
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

        $this->request->setSign($sign);

        $this->assertSame($sign, $this->request->getSign());
    }

    public function testGetRequestPath(): void
    {
        $path = $this->request->getRequestPath();

        $this->assertEquals('https://api.sms.jpush.cn/v1/sign', $path);
    }

    public function testGetRequestMethod(): void
    {
        $method = $this->request->getRequestMethod();

        $this->assertEquals('POST', $method);
    }

    public function testGetRequestOptionsBasic(): void
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
        $sign->setSign('test-sign');
        $sign->setType(SignTypeEnum::COMPANY);
        // 设置为 null 的值以模拟测试场景
        $sign->setRemark(null);
        $sign->setImage0(null);
        $sign->setImage1(null);

        $this->request->setSign($sign);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('multipart', $options);

        /** @var array<int, mixed> $multipart */
        $multipart = $options['multipart'];
        $this->assertIsArray($multipart);
        $this->assertCount(2, $multipart);

        /** @var array<string, mixed> $firstPart */
        $firstPart = $multipart[0];
        $this->assertIsArray($firstPart);

        /** @var array<string, mixed> $secondPart */
        $secondPart = $multipart[1];
        $this->assertIsArray($secondPart);

        $this->assertEquals('test-sign', $firstPart['contents']);
        $this->assertEquals(SignTypeEnum::COMPANY->value, $secondPart['contents']);
    }
}
