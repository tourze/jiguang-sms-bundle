<?php

namespace JiguangSmsBundle\Tests\Unit\Exception;

use JiguangSmsBundle\Exception\JiguangSmsException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class JiguangSmsExceptionTest extends TestCase
{
    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new JiguangSmsException();

        $this->assertInstanceOf(RuntimeException::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = 'Test exception message';
        $exception = new JiguangSmsException($message);

        $this->assertEquals($message, $exception->getMessage());
    }
}