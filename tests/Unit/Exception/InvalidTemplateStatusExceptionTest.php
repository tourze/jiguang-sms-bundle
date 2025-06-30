<?php

namespace JiguangSmsBundle\Tests\Unit\Exception;

use JiguangSmsBundle\Exception\InvalidTemplateStatusException;
use JiguangSmsBundle\Exception\JiguangSmsException;
use PHPUnit\Framework\TestCase;

class InvalidTemplateStatusExceptionTest extends TestCase
{
    public function testExceptionExtendsJiguangSmsException(): void
    {
        $exception = new InvalidTemplateStatusException();

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