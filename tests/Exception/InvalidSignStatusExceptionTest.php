<?php

namespace JiguangSmsBundle\Tests\Exception;

use JiguangSmsBundle\Exception\InvalidSignStatusException;
use JiguangSmsBundle\Exception\JiguangSmsException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(InvalidSignStatusException::class)]
final class InvalidSignStatusExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 空实现，因为此测试不需要特殊的设置
    }

    public function testExceptionExtendsJiguangSmsException(): void
    {
        $exception = new InvalidSignStatusException();

        $this->assertInstanceOf(InvalidSignStatusException::class, $exception);
        $this->assertInstanceOf(JiguangSmsException::class, $exception);
    }

    public function testExceptionWithoutStatus(): void
    {
        $exception = new InvalidSignStatusException();

        $this->assertEquals('无效的签名状态: ', $exception->getMessage());
    }

    public function testExceptionWithStatus(): void
    {
        $status = 'invalid_status';
        $exception = new InvalidSignStatusException($status);

        $this->assertEquals('无效的签名状态: ' . $status, $exception->getMessage());
    }
}
