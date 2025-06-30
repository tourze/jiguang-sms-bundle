<?php

namespace JiguangSmsBundle\Tests\Unit\Enum;

use JiguangSmsBundle\Enum\CodeTypeEnum;
use PHPUnit\Framework\TestCase;

class CodeTypeEnumTest extends TestCase
{
    public function test_cases_haveCorrectValues(): void
    {
        $this->assertEquals(1, CodeTypeEnum::TEXT->value);
        $this->assertEquals(2, CodeTypeEnum::VOICE->value);
    }

    public function test_getLabel_returnsCorrectLabels(): void
    {
        $this->assertEquals('文本验证码', CodeTypeEnum::TEXT->getLabel());
        $this->assertEquals('语音验证码', CodeTypeEnum::VOICE->getLabel());
    }

    public function test_implementsInterfaces(): void
    {
        $this->assertInstanceOf(\BackedEnum::class, CodeTypeEnum::TEXT);
        $this->assertInstanceOf(\UnitEnum::class, CodeTypeEnum::TEXT);
    }

    public function test_allCasesAreHandled(): void
    {
        $cases = CodeTypeEnum::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(CodeTypeEnum::TEXT, $cases);
        $this->assertContains(CodeTypeEnum::VOICE, $cases);
    }
}