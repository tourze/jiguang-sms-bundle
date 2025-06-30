<?php

namespace JiguangSmsBundle\Tests\Unit;

use JiguangSmsBundle\JiguangSmsBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JiguangSmsBundleTest extends TestCase
{
    public function testBundleExtendsBundle(): void
    {
        $bundle = new JiguangSmsBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}