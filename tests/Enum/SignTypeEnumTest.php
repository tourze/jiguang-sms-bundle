<?php

namespace JiguangSmsBundle\Tests\Enum;

use JiguangSmsBundle\Enum\SignTypeEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(SignTypeEnum::class)]
final class SignTypeEnumTest extends AbstractEnumTestCase
{
    public function testCasesHaveCorrectValues(): void
    {
        $this->assertEquals(1, SignTypeEnum::COMPANY->value);
        $this->assertEquals(2, SignTypeEnum::ICP_WEBSITE->value);
        $this->assertEquals(3, SignTypeEnum::APP->value);
        $this->assertEquals(4, SignTypeEnum::WECHAT->value);
        $this->assertEquals(5, SignTypeEnum::TRADEMARK->value);
        $this->assertEquals(6, SignTypeEnum::OTHER->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        $this->assertEquals('公司名称全称或简称', SignTypeEnum::COMPANY->getLabel());
        $this->assertEquals('工信部备案的网站全称或简称', SignTypeEnum::ICP_WEBSITE->getLabel());
        $this->assertEquals('APP应用名称或简称', SignTypeEnum::APP->getLabel());
        $this->assertEquals('公众号小程序全称或简称', SignTypeEnum::WECHAT->getLabel());
        $this->assertEquals('商标名称全称或简称', SignTypeEnum::TRADEMARK->getLabel());
        $this->assertEquals('其他', SignTypeEnum::OTHER->getLabel());
    }

    public function testGetDescriptionReturnsDetailedDescriptions(): void
    {
        $this->assertStringContainsString('营业执照复印件图片', SignTypeEnum::COMPANY->getDescription());
        $this->assertStringContainsString('icp备案截图', SignTypeEnum::ICP_WEBSITE->getDescription());
        $this->assertStringContainsString('应用商店的下载链接', SignTypeEnum::APP->getDescription());
        $this->assertStringContainsString('公众号小程序', SignTypeEnum::WECHAT->getDescription());
        $this->assertStringContainsString('商标注册证书', SignTypeEnum::TRADEMARK->getDescription());
        $this->assertStringContainsString('第三方授权委托书', SignTypeEnum::OTHER->getDescription());
    }

    public function testAllCasesAreHandled(): void
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

    public function testToArray(): void
    {
        // Test toArray() on each enum instance
        $company = SignTypeEnum::COMPANY->toArray();
        $this->assertIsArray($company);
        $this->assertEquals(['value' => 1, 'label' => '公司名称全称或简称'], $company);

        $icpWebsite = SignTypeEnum::ICP_WEBSITE->toArray();
        $this->assertIsArray($icpWebsite);
        $this->assertEquals(['value' => 2, 'label' => '工信部备案的网站全称或简称'], $icpWebsite);

        $app = SignTypeEnum::APP->toArray();
        $this->assertIsArray($app);
        $this->assertEquals(['value' => 3, 'label' => 'APP应用名称或简称'], $app);

        $wechat = SignTypeEnum::WECHAT->toArray();
        $this->assertIsArray($wechat);
        $this->assertEquals(['value' => 4, 'label' => '公众号小程序全称或简称'], $wechat);

        $trademark = SignTypeEnum::TRADEMARK->toArray();
        $this->assertIsArray($trademark);
        $this->assertEquals(['value' => 5, 'label' => '商标名称全称或简称'], $trademark);

        $other = SignTypeEnum::OTHER->toArray();
        $this->assertIsArray($other);
        $this->assertEquals(['value' => 6, 'label' => '其他'], $other);
    }

    public function testGenOptions(): void
    {
        $options = SignTypeEnum::genOptions();
        $this->assertIsArray($options);
        $this->assertCount(6, $options);

        foreach ($options as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
            $this->assertArrayHasKey('text', $item);
            $this->assertArrayHasKey('name', $item);
        }

        // Test first and last option - using ItemTrait's toSelectItem() format
        $this->assertEquals([
            'label' => '公司名称全称或简称',
            'text' => '公司名称全称或简称',
            'value' => 1,
            'name' => '公司名称全称或简称',
        ], $options[0]);

        $this->assertEquals([
            'label' => '其他',
            'text' => '其他',
            'value' => 6,
            'name' => '其他',
        ], $options[5]);
    }
}
