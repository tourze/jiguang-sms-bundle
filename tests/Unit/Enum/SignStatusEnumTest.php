<?php

namespace JiguangSmsBundle\Tests\Unit\Enum;

use JiguangSmsBundle\Enum\SignStatusEnum;
use PHPUnit\Framework\TestCase;

class SignStatusEnumTest extends TestCase
{
    public function test_cases_haveCorrectValues(): void
    {
        $this->assertEquals(0, SignStatusEnum::PENDING->value);
        $this->assertEquals(1, SignStatusEnum::APPROVED->value);
        $this->assertEquals(2, SignStatusEnum::REJECTED->value);
        $this->assertEquals(3, SignStatusEnum::DELETED->value);
    }

    public function test_getLabel_returnsCorrectLabels(): void
    {
        $this->assertEquals('审核中', SignStatusEnum::PENDING->getLabel());
        $this->assertEquals('审核通过', SignStatusEnum::APPROVED->getLabel());
        $this->assertEquals('审核不通过', SignStatusEnum::REJECTED->getLabel());
        $this->assertEquals('已删除', SignStatusEnum::DELETED->getLabel());
    }

    public function test_allCasesAreHandled(): void
    {
        $cases = SignStatusEnum::cases();
        $this->assertCount(4, $cases);
        $this->assertContains(SignStatusEnum::PENDING, $cases);
        $this->assertContains(SignStatusEnum::APPROVED, $cases);
        $this->assertContains(SignStatusEnum::REJECTED, $cases);
        $this->assertContains(SignStatusEnum::DELETED, $cases);
    }
}