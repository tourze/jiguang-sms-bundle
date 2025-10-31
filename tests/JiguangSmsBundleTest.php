<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Tests;

use JiguangSmsBundle\JiguangSmsBundle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(JiguangSmsBundle::class)]
#[RunTestsInSeparateProcesses]
final class JiguangSmsBundleTest extends AbstractBundleTestCase
{
}
