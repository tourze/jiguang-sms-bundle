<?php

namespace JiguangSmsBundle\Tests\Enum;

use JiguangSmsBundle\Enum\TemplateTypeEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(TemplateTypeEnum::class)]
final class TemplateTypeEnumTest extends AbstractEnumTestCase
{
    public function testCasesHaveCorrectValues(): void
    {
        $this->assertEquals(1, TemplateTypeEnum::VERIFICATION->value);
        $this->assertEquals(2, TemplateTypeEnum::NOTIFICATION->value);
        $this->assertEquals(3, TemplateTypeEnum::MARKETING->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        $this->assertEquals('验证码类', TemplateTypeEnum::VERIFICATION->getLabel());
        $this->assertEquals('通知类', TemplateTypeEnum::NOTIFICATION->getLabel());
        $this->assertEquals('营销类', TemplateTypeEnum::MARKETING->getLabel());
    }

    public function testAllCasesAreHandled(): void
    {
        $cases = TemplateTypeEnum::cases();
        $this->assertCount(3, $cases);
        $this->assertContains(TemplateTypeEnum::VERIFICATION, $cases);
        $this->assertContains(TemplateTypeEnum::NOTIFICATION, $cases);
        $this->assertContains(TemplateTypeEnum::MARKETING, $cases);
    }

    public function testToArray(): void
    {
        // Test toArray() on each enum instance
        $verification = TemplateTypeEnum::VERIFICATION->toArray();
        $this->assertIsArray($verification);
        $this->assertEquals(['value' => 1, 'label' => '验证码类'], $verification);

        $notification = TemplateTypeEnum::NOTIFICATION->toArray();
        $this->assertIsArray($notification);
        $this->assertEquals(['value' => 2, 'label' => '通知类'], $notification);

        $marketing = TemplateTypeEnum::MARKETING->toArray();
        $this->assertIsArray($marketing);
        $this->assertEquals(['value' => 3, 'label' => '营销类'], $marketing);
    }

    public function testGenOptions(): void
    {
        $options = TemplateTypeEnum::genOptions();
        $this->assertIsArray($options);
        $this->assertCount(3, $options);

        foreach ($options as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
            $this->assertArrayHasKey('text', $item);
            $this->assertArrayHasKey('name', $item);
        }

        // Test specific values - using ItemTrait's toSelectItem() format
        $this->assertEquals([
            'label' => '验证码类',
            'text' => '验证码类',
            'value' => 1,
            'name' => '验证码类',
        ], $options[0]);

        $this->assertEquals([
            'label' => '通知类',
            'text' => '通知类',
            'value' => 2,
            'name' => '通知类',
        ], $options[1]);

        $this->assertEquals([
            'label' => '营销类',
            'text' => '营销类',
            'value' => 3,
            'name' => '营销类',
        ], $options[2]);
    }
}
