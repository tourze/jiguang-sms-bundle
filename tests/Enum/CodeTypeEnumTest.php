<?php

namespace JiguangSmsBundle\Tests\Enum;

use JiguangSmsBundle\Enum\CodeTypeEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(CodeTypeEnum::class)]
final class CodeTypeEnumTest extends AbstractEnumTestCase
{
    public function testCasesHaveCorrectValues(): void
    {
        $this->assertEquals(1, CodeTypeEnum::TEXT->value);
        $this->assertEquals(2, CodeTypeEnum::VOICE->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        $this->assertEquals('文本验证码', CodeTypeEnum::TEXT->getLabel());
        $this->assertEquals('语音验证码', CodeTypeEnum::VOICE->getLabel());
    }

    public function testImplementsInterfaces(): void
    {
        $this->assertNotNull(CodeTypeEnum::TEXT);
    }

    public function testAllCasesAreHandled(): void
    {
        $cases = CodeTypeEnum::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(CodeTypeEnum::TEXT, $cases);
        $this->assertContains(CodeTypeEnum::VOICE, $cases);
    }

    public function testToArray(): void
    {
        // Test toArray() on each enum instance
        $text = CodeTypeEnum::TEXT->toArray();
        $this->assertIsArray($text);
        $this->assertEquals(['value' => 1, 'label' => '文本验证码'], $text);

        $voice = CodeTypeEnum::VOICE->toArray();
        $this->assertIsArray($voice);
        $this->assertEquals(['value' => 2, 'label' => '语音验证码'], $voice);
    }

    public function testGenOptions(): void
    {
        $options = CodeTypeEnum::genOptions();
        $this->assertIsArray($options);
        $this->assertCount(2, $options);

        foreach ($options as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
            $this->assertArrayHasKey('text', $item);
            $this->assertArrayHasKey('name', $item);
        }

        // Test specific values - using ItemTrait's toSelectItem() format
        $this->assertEquals([
            'label' => '文本验证码',
            'text' => '文本验证码',
            'value' => 1,
            'name' => '文本验证码',
        ], $options[0]);

        $this->assertEquals([
            'label' => '语音验证码',
            'text' => '语音验证码',
            'value' => 2,
            'name' => '语音验证码',
        ], $options[1]);
    }
}
