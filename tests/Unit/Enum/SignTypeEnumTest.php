<?php

namespace JiguangSmsBundle\Tests\Unit\Enum;

use JiguangSmsBundle\Enum\SignTypeEnum;
use PHPUnit\Framework\TestCase;

class SignTypeEnumTest extends TestCase
{
    public function test_cases_haveCorrectValues(): void
    {
        $this->assertEquals(1, SignTypeEnum::COMPANY->value);
        $this->assertEquals(2, SignTypeEnum::ICP_WEBSITE->value);
        $this->assertEquals(3, SignTypeEnum::APP->value);
        $this->assertEquals(4, SignTypeEnum::WECHAT->value);
        $this->assertEquals(5, SignTypeEnum::TRADEMARK->value);
        $this->assertEquals(6, SignTypeEnum::OTHER->value);
    }

    public function test_getLabel_returnsCorrectLabels(): void
    {
        $this->assertEquals('公司名称全称或简称', SignTypeEnum::COMPANY->getLabel());
        $this->assertEquals('工信部备案的网站全称或简称', SignTypeEnum::ICP_WEBSITE->getLabel());
        $this->assertEquals('APP应用名称或简称', SignTypeEnum::APP->getLabel());
        $this->assertEquals('公众号小程序全称或简称', SignTypeEnum::WECHAT->getLabel());
        $this->assertEquals('商标名称全称或简称', SignTypeEnum::TRADEMARK->getLabel());
        $this->assertEquals('其他', SignTypeEnum::OTHER->getLabel());
    }

    public function test_getDescription_returnsDetailedDescriptions(): void
    {
        $this->assertStringContainsString('营业执照复印件图片', SignTypeEnum::COMPANY->getDescription());
        $this->assertStringContainsString('icp备案截图', SignTypeEnum::ICP_WEBSITE->getDescription());
        $this->assertStringContainsString('应用商店的下载链接', SignTypeEnum::APP->getDescription());
        $this->assertStringContainsString('公众号小程序', SignTypeEnum::WECHAT->getDescription());
        $this->assertStringContainsString('商标注册证书', SignTypeEnum::TRADEMARK->getDescription());
        $this->assertStringContainsString('第三方授权委托书', SignTypeEnum::OTHER->getDescription());
    }

    public function test_allCasesAreHandled(): void
    {
        $cases = SignTypeEnum::cases();
        $this->assertCount(6, $cases);
        $this->assertContains(SignTypeEnum::COMPANY, $cases);
        $this->assertContains(SignTypeEnum::ICP_WEBSITE, $cases);
        $this->assertContains(SignTypeEnum::APP, $cases);
        $this->assertContains(SignTypeEnum::WECHAT, $cases);
        $this->assertContains(SignTypeEnum::TRADEMARK, $cases);
        $this->assertContains(SignTypeEnum::OTHER, $cases);
    }
}