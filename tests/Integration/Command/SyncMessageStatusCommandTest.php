<?php

namespace JiguangSmsBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Command\SyncMessageStatusCommand;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Repository\AccountRepository;
use JiguangSmsBundle\Repository\MessageRepository;
use JiguangSmsBundle\Repository\TextCodeRepository;
use JiguangSmsBundle\Repository\VoiceCodeRepository;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncMessageStatusCommandTest extends TestCase
{
    private SyncMessageStatusCommand $command;
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;
    private MessageRepository $messageRepository;
    private TextCodeRepository $textCodeRepository;
    private VoiceCodeRepository $voiceCodeRepository;
    private JiguangSmsService $jiguangSmsService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->messageRepository = $this->createMock(MessageRepository::class);
        $this->textCodeRepository = $this->createMock(TextCodeRepository::class);
        $this->voiceCodeRepository = $this->createMock(VoiceCodeRepository::class);
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);

        $this->command = new SyncMessageStatusCommand(
            $this->entityManager,
            $this->accountRepository,
            $this->messageRepository,
            $this->textCodeRepository,
            $this->voiceCodeRepository,
            $this->jiguangSmsService
        );
    }

    public function testExecute(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $account = new Account();
        $this->accountRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$account]);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn([]);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->command->run($input, $output);

        $this->assertEquals(0, $result);
    }
}