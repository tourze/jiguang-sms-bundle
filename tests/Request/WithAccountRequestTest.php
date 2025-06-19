<?php

namespace JiguangSmsBundle\Tests\Request;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Request\WithAccountRequest;
use PHPUnit\Framework\TestCase;

class WithAccountRequestTest extends TestCase
{
    /**
     * @var WithAccountRequest
     */
    private $request;

    protected function setUp(): void
    {
        // 创建WithAccountRequest的具体实现，因为它是抽象类
        $this->request = new class extends WithAccountRequest {
            public function getRequestPath(): string
            {
                return '/test/path';
            }

            public function getRequestMethod(): string
            {
                return 'POST';
            }

            public function getRequestOptions(): array
            {
                return ['query' => ['param' => 'value']];
            }
        };
    }

    public function testAccountAssociation(): void
    {
        $account = new Account();
        $account->setAppKey('test_app_key');
        $account->setMasterSecret('test_master_secret');

        $this->request->setAccount($account);

        $this->assertSame($account, $this->request->getAccount());
    }

    public function testRequestProperties(): void
    {
        $this->assertEquals('/test/path', $this->request->getRequestPath());
        $this->assertEquals('POST', $this->request->getRequestMethod());

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('query', $options);
        $this->assertEquals(['param' => 'value'], $options['query']);
    }
}
