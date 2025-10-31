<?php

namespace JiguangSmsBundle\Tests\Exception;

use JiguangSmsBundle\Exception\JiguangSmsException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(JiguangSmsException::class)]
final class JiguangSmsExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 空实现，因为此测试不需要特殊的设置
    }

    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new class extends JiguangSmsException {};

        $this->assertInstanceOf(JiguangSmsException::class, $exception);
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = 'Test exception message';
        $exception = new class($message) extends JiguangSmsException {
            public function __construct(string $message = '')
            {
                parent::__construct($message);
            }
        };

        $this->assertEquals($message, $exception->getMessage());
    }
}
