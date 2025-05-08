<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $template = new Template();

        // 测试模板ID
        $template->setTempId(123);
        $this->assertEquals(123, $template->getTempId());

        // 测试模板类型
        $template->setType(TemplateTypeEnum::VERIFICATION);
        $this->assertEquals(TemplateTypeEnum::VERIFICATION, $template->getType());

        // 测试模板内容
        $template->setTemplate('测试模板内容');
        $this->assertEquals('测试模板内容', $template->getTemplate());

        // 测试有效期
        $template->setTtl(300);
        $this->assertEquals(300, $template->getTtl());

        // 测试备注
        $template->setRemark('测试备注');
        $this->assertEquals('测试备注', $template->getRemark());

        // 测试审核状态
        $template->setStatus(TemplateStatusEnum::APPROVED);
        $this->assertEquals(TemplateStatusEnum::APPROVED, $template->getStatus());

        // 测试使用状态
        $template->setUseStatus(true);
        $this->assertTrue($template->isUseStatus());

        // 测试创建时间和更新时间
        $now = new \DateTime();
        $template->setCreateTime($now);
        $this->assertSame($now, $template->getCreateTime());

        $updateTime = new \DateTime('+1 hour');
        $template->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $template->getUpdateTime());
    }

    public function testDefaultValues(): void
    {
        $template = new Template();

        $this->assertEquals(0, $template->getId());
        $this->assertNull($template->getTempId());

        // 模板类型默认值是VERIFICATION (1)
        $this->assertEquals(TemplateTypeEnum::VERIFICATION, $template->getType());

        // template是非空字段，不能测试默认值为null
        // $this->assertNull($template->getTemplate());

        $this->assertNull($template->getTtl());
        $this->assertNull($template->getRemark());
        $this->assertEquals(TemplateStatusEnum::PENDING, $template->getStatus());
        $this->assertFalse($template->isUseStatus());
        $this->assertNull($template->getCreateTime());
        $this->assertNull($template->getUpdateTime());
    }

    public function testAccountRelation(): void
    {
        $template = new Template();
        $account = new Account();
        $account->setTitle('测试账号');
        $account->setAppKey('test_app_key');

        $template->setAccount($account);

        $this->assertSame($account, $template->getAccount());
    }

    public function testStatusEnumValidation(): void
    {
        $template = new Template();

        $template->setStatus(TemplateStatusEnum::PENDING);
        $this->assertEquals(TemplateStatusEnum::PENDING, $template->getStatus());

        $template->setStatus(TemplateStatusEnum::APPROVED);
        $this->assertEquals(TemplateStatusEnum::APPROVED, $template->getStatus());

        $template->setStatus(TemplateStatusEnum::REJECTED);
        $this->assertEquals(TemplateStatusEnum::REJECTED, $template->getStatus());
    }
}
