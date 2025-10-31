<?php

namespace JiguangSmsBundle\Tests\Exception;

use JiguangSmsBundle\Exception\InvalidTemplateStatusException;
use JiguangSmsBundle\Exception\JiguangSmsException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(InvalidTemplateStatusException::class)]
final class InvalidTemplateStatusExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 空实现，因为此测试不需要特殊的设置
    }

    public function testExceptionExtendsJiguangSmsException(): void
    {
        $exception = new InvalidTemplateStatusException();

        $this->assertInstanceOf(InvalidTemplateStatusException::class, $exception);
        $this->assertInstanceOf(JiguangSmsException::class, $exception);
    }

    public function testExceptionWithoutStatus(): void
    {
        $exception = new InvalidTemplateStatusException();

        $this->assertEquals('无效的模板状态: ', $exception->getMessage());
    }

    public function testExceptionWithStatus(): void
    {
        $status = 'invalid_status';
        $exception = new InvalidTemplateStatusException($status);

        $this->assertEquals('无效的模板状态: ' . $status, $exception->getMessage());
    }
}
