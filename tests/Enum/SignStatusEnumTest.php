<?php

namespace JiguangSmsBundle\Tests\Enum;

use JiguangSmsBundle\Enum\SignStatusEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(SignStatusEnum::class)]
final class SignStatusEnumTest extends AbstractEnumTestCase
{
    public function testCasesHaveCorrectValues(): void
    {
        $this->assertEquals(0, SignStatusEnum::PENDING->value);
        $this->assertEquals(1, SignStatusEnum::APPROVED->value);
        $this->assertEquals(2, SignStatusEnum::REJECTED->value);
        $this->assertEquals(3, SignStatusEnum::DELETED->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        $this->assertEquals('审核中', SignStatusEnum::PENDING->getLabel());
        $this->assertEquals('审核通过', SignStatusEnum::APPROVED->getLabel());
        $this->assertEquals('审核不通过', SignStatusEnum::REJECTED->getLabel());
        $this->assertEquals('已删除', SignStatusEnum::DELETED->getLabel());
    }

    public function testAllCasesAreHandled(): void
    {
        $cases = SignStatusEnum::cases();
        $this->assertCount(4, $cases);
        $this->assertContains(SignStatusEnum::PENDING, $cases);
        $this->assertContains(SignStatusEnum::APPROVED, $cases);
        $this->assertContains(SignStatusEnum::REJECTED, $cases);
        $this->assertContains(SignStatusEnum::DELETED, $cases);
    }

    public function testToArray(): void
    {
        // Test toArray() on each enum instance
        $pending = SignStatusEnum::PENDING->toArray();
        $this->assertIsArray($pending);
        $this->assertEquals(['value' => 0, 'label' => '审核中'], $pending);

        $approved = SignStatusEnum::APPROVED->toArray();
        $this->assertIsArray($approved);
        $this->assertEquals(['value' => 1, 'label' => '审核通过'], $approved);

        $rejected = SignStatusEnum::REJECTED->toArray();
        $this->assertIsArray($rejected);
        $this->assertEquals(['value' => 2, 'label' => '审核不通过'], $rejected);

        $deleted = SignStatusEnum::DELETED->toArray();
        $this->assertIsArray($deleted);
        $this->assertEquals(['value' => 3, 'label' => '已删除'], $deleted);
    }

    public function testGenOptions(): void
    {
        $options = SignStatusEnum::genOptions();
        $this->assertIsArray($options);
        $this->assertCount(4, $options);

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

        $this->assertEquals([
            'label' => '已删除',
            'text' => '已删除',
            'value' => 3,
            'name' => '已删除',
        ], $options[3]);
    }
}
