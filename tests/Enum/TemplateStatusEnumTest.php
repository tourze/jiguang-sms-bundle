<?php

namespace JiguangSmsBundle\Tests\Enum;

use JiguangSmsBundle\Enum\TemplateStatusEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(TemplateStatusEnum::class)]
final class TemplateStatusEnumTest extends AbstractEnumTestCase
{
    public function testCasesHaveCorrectValues(): void
    {
        $this->assertEquals(0, TemplateStatusEnum::PENDING->value);
        $this->assertEquals(1, TemplateStatusEnum::APPROVED->value);
        $this->assertEquals(2, TemplateStatusEnum::REJECTED->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        $this->assertEquals('审核中', TemplateStatusEnum::PENDING->getLabel());
        $this->assertEquals('审核通过', TemplateStatusEnum::APPROVED->getLabel());
        $this->assertEquals('审核不通过', TemplateStatusEnum::REJECTED->getLabel());
    }

    public function testAllCasesAreHandled(): void
    {
        $cases = TemplateStatusEnum::cases();
        $this->assertCount(3, $cases);
        $this->assertContains(TemplateStatusEnum::PENDING, $cases);
        $this->assertContains(TemplateStatusEnum::APPROVED, $cases);
        $this->assertContains(TemplateStatusEnum::REJECTED, $cases);
    }

    public function testToArray(): void
    {
        // Test toArray() on each enum instance
        $pending = TemplateStatusEnum::PENDING->toArray();
        $this->assertIsArray($pending);
        $this->assertEquals(['value' => 0, 'label' => '审核中'], $pending);

        $approved = TemplateStatusEnum::APPROVED->toArray();
        $this->assertIsArray($approved);
        $this->assertEquals(['value' => 1, 'label' => '审核通过'], $approved);

        $rejected = TemplateStatusEnum::REJECTED->toArray();
        $this->assertIsArray($rejected);
        $this->assertEquals(['value' => 2, 'label' => '审核不通过'], $rejected);
    }

    public function testGenOptions(): void
    {
        $options = TemplateStatusEnum::genOptions();
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
            'label' => '审核中',
            'text' => '审核中',
            'value' => 0,
            'name' => '审核中',
        ], $options[0]);

        $this->assertEquals([
            'label' => '审核通过',
            'text' => '审核通过',
            'value' => 1,
            'name' => '审核通过',
        ], $options[1]);

        $this->assertEquals([
            'label' => '审核不通过',
            'text' => '审核不通过',
            'value' => 2,
            'name' => '审核不通过',
        ], $options[2]);
    }
}
