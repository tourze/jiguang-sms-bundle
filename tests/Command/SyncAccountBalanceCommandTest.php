<?php

namespace JiguangSmsBundle\Tests\Command;

use JiguangSmsBundle\Command\SyncAccountBalanceCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;

/**
 * @internal
 */
#[CoversClass(SyncAccountBalanceCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncAccountBalanceCommandTest extends AbstractCommandTestCase
{
    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncAccountBalanceCommand::class);

        return new CommandTester($command);
    }

    protected function onSetUp(): void
    {
        // 仅测试命令的实例化和基本结构
    }

    public function testCommandCanBeInstantiated(): void
    {
        $commandTester = $this->getCommandTester();
        $this->assertInstanceOf(CommandTester::class, $commandTester);
    }
}
