<?php

namespace JiguangSmsBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Command\SyncCodeVerifyStatusCommand;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Repository\TextCodeRepository;
use JiguangSmsBundle\Repository\VoiceCodeRepository;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCodeVerifyStatusCommandTest extends TestCase
{
    private SyncCodeVerifyStatusCommand $command;
    private EntityManagerInterface $entityManager;
    private JiguangSmsService $jiguangSmsService;
    private TextCodeRepository $textCodeRepository;
    private VoiceCodeRepository $voiceCodeRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->textCodeRepository = $this->createMock(TextCodeRepository::class);
        $this->voiceCodeRepository = $this->createMock(VoiceCodeRepository::class);

        $this->command = new SyncCodeVerifyStatusCommand(
            $this->entityManager,
            $this->jiguangSmsService,
            $this->textCodeRepository,
            $this->voiceCodeRepository
        );
    }

    public function testExecute(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $textCode = $this->createMock(TextCode::class);
        
        $this->textCodeRepository->expects($this->once())
            ->method('findUnverifiedAndNotExpired')
            ->willReturn([$textCode]);

        $this->voiceCodeRepository->expects($this->once())
            ->method('findUnverifiedAndNotExpired')
            ->willReturn([]);

        $textCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn('test-msg-id');

        $account = $this->createMock(Account::class);
        $textCode->expects($this->once())
            ->method('getAccount')
            ->willReturn($account);

        $textCode->expects($this->once())
            ->method('getCode')
            ->willReturn('1234');

        $this->jiguangSmsService->expects($this->once())
            ->method('request');

        $textCode->expects($this->once())
            ->method('setVerified')
            ->with(true);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->command->run($input, $output);

        $this->assertEquals(0, $result);
    }
}