<?php

namespace JiguangSmsBundle\Tests\Command;

use JiguangSmsBundle\Command\SyncTemplateStatusCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;

/**
 * @internal
 */
#[CoversClass(SyncTemplateStatusCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncTemplateStatusCommandTest extends AbstractCommandTestCase
{
    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncTemplateStatusCommand::class);

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
