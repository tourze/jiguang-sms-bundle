<?php

namespace JiguangSmsBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Command\SyncSignStatusCommand;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Repository\SignRepository;
use JiguangSmsBundle\Service\SignService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncSignStatusCommandTest extends TestCase
{
    private SyncSignStatusCommand $command;
    private EntityManagerInterface $entityManager;
    private SignService $signService;
    private SignRepository $signRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->signService = $this->createMock(SignService::class);
        $this->signRepository = $this->createMock(SignRepository::class);

        $this->command = new SyncSignStatusCommand(
            $this->entityManager,
            $this->signService,
            $this->signRepository
        );
    }

    public function testExecute(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $sign = $this->createMock(Sign::class);
        $this->signRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$sign]);

        $sign->expects($this->once())
            ->method('setSyncing')
            ->with(true);

        $sign->expects($this->once())
            ->method('getSign')
            ->willReturn('test-sign');

        $this->signService->expects($this->once())
            ->method('syncSignStatus')
            ->with($sign);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->command->run($input, $output);

        $this->assertEquals(0, $result);
    }
}