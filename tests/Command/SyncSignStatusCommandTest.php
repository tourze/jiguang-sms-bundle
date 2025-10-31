<?php

namespace JiguangSmsBundle\Tests\Command;

use JiguangSmsBundle\Command\SyncSignStatusCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;

/**
 * @internal
 */
#[CoversClass(SyncSignStatusCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncSignStatusCommandTest extends AbstractCommandTestCase
{
    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncSignStatusCommand::class);

        return new CommandTester($command);
    }

    protected function onSetUp(): void
    {
        // 空实现，测试不需要特殊初始化
    }

    public function testCommandCanBeInstantiated(): void
    {
        $commandTester = $this->getCommandTester();
        $this->assertInstanceOf(CommandTester::class, $commandTester);
    }
}
