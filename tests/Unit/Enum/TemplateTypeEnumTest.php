<?php

namespace JiguangSmsBundle\Tests\Unit\Enum;

use JiguangSmsBundle\Enum\TemplateTypeEnum;
use PHPUnit\Framework\TestCase;

class TemplateTypeEnumTest extends TestCase
{
    public function test_cases_haveCorrectValues(): void
    {
        $this->assertEquals(1, TemplateTypeEnum::VERIFICATION->value);
        $this->assertEquals(2, TemplateTypeEnum::NOTIFICATION->value);
        $this->assertEquals(3, TemplateTypeEnum::MARKETING->value);
    }

    public function test_getLabel_returnsCorrectLabels(): void
    {
        $this->assertEquals('验证码类', TemplateTypeEnum::VERIFICATION->getLabel());
        $this->assertEquals('通知类', TemplateTypeEnum::NOTIFICATION->getLabel());
        $this->assertEquals('营销类', TemplateTypeEnum::MARKETING->getLabel());
    }

    public function test_allCasesAreHandled(): void
    {
        $cases = TemplateTypeEnum::cases();
        $this->assertCount(3, $cases);
        $this->assertContains(TemplateTypeEnum::VERIFICATION, $cases);
        $this->assertContains(TemplateTypeEnum::NOTIFICATION, $cases);
        $this->assertContains(TemplateTypeEnum::MARKETING, $cases);
    }
}