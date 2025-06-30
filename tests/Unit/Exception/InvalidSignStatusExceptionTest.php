<?php

namespace JiguangSmsBundle\Tests\Unit\Exception;

use JiguangSmsBundle\Exception\InvalidSignStatusException;
use JiguangSmsBundle\Exception\JiguangSmsException;
use PHPUnit\Framework\TestCase;

class InvalidSignStatusExceptionTest extends TestCase
{
    public function testExceptionExtendsJiguangSmsException(): void
    {
        $exception = new InvalidSignStatusException();

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