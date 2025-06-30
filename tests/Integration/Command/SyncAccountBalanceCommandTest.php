<?php

namespace JiguangSmsBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Command\SyncAccountBalanceCommand;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\AccountBalance;
use JiguangSmsBundle\Repository\AccountBalanceRepository;
use JiguangSmsBundle\Repository\AccountRepository;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncAccountBalanceCommandTest extends TestCase
{
    private SyncAccountBalanceCommand $command;
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;
    private AccountBalanceRepository $accountBalanceRepository;
    private JiguangSmsService $jiguangSmsService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->accountBalanceRepository = $this->createMock(AccountBalanceRepository::class);
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);

        $this->command = new SyncAccountBalanceCommand(
            $this->entityManager,
            $this->accountRepository,
            $this->accountBalanceRepository,
            $this->jiguangSmsService
        );
    }

    public function testExecute(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $account = new Account();
        $this->accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn([
                'dev_balance' => 100,
                'voice_balance' => 50,
                'industry_balance' => 75,
                'market_balance' => 25
            ]);

        $this->accountBalanceRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['account' => $account])
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(AccountBalance::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->command->run($input, $output);

        $this->assertEquals(0, $result);
    }
}