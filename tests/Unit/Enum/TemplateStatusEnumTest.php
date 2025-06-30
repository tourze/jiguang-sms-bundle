<?php

namespace JiguangSmsBundle\Tests\Unit\Enum;

use JiguangSmsBundle\Enum\TemplateStatusEnum;
use PHPUnit\Framework\TestCase;

class TemplateStatusEnumTest extends TestCase
{
    public function test_cases_haveCorrectValues(): void
    {
        $this->assertEquals(0, TemplateStatusEnum::PENDING->value);
        $this->assertEquals(1, TemplateStatusEnum::APPROVED->value);
        $this->assertEquals(2, TemplateStatusEnum::REJECTED->value);
    }

    public function test_getLabel_returnsCorrectLabels(): void
    {
        $this->assertEquals('审核中', TemplateStatusEnum::PENDING->getLabel());
        $this->assertEquals('审核通过', TemplateStatusEnum::APPROVED->getLabel());
        $this->assertEquals('审核不通过', TemplateStatusEnum::REJECTED->getLabel());
    }

    public function test_allCasesAreHandled(): void
    {
        $cases = TemplateStatusEnum::cases();
        $this->assertCount(3, $cases);
        $this->assertContains(TemplateStatusEnum::PENDING, $cases);
        $this->assertContains(TemplateStatusEnum::APPROVED, $cases);
        $this->assertContains(TemplateStatusEnum::REJECTED, $cases);
    }
}