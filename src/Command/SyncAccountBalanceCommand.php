<?php

namespace JiguangSmsBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\AccountBalance;
use JiguangSmsBundle\Repository\AccountBalanceRepository;
use JiguangSmsBundle\Repository\AccountRepository;
use JiguangSmsBundle\Request\Account\GetBalanceRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;

#[AsCronTask(expression: '*/10 * * * *')]
#[AsCommand(
    name: self::NAME,
    description: '同步极光短信账户余量',
)]
class SyncAccountBalanceCommand extends Command
{
    public const NAME = 'jiguang:sms:sync-account-balance';
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AccountRepository $accountRepository,
        private readonly AccountBalanceRepository $accountBalanceRepository,
        private readonly JiguangSmsService $jiguangSmsService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->accountRepository->findBy(['valid' => true]);

        foreach ($accounts as $account) {
            $this->syncAccountBalance($account);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }

    private function syncAccountBalance(Account $account): void
    {
        $request = new GetBalanceRequest();
        $request->setAccount($account);

        $response = $this->jiguangSmsService->request($request);

        $balance = $this->accountBalanceRepository->findOneBy(['account' => $account]) ?? new AccountBalance();
        $balance->setAccount($account)
            ->setBalance($response['dev_balance'] ?? null)
            ->setVoice($response['voice_balance'] ?? null)
            ->setIndustry($response['industry_balance'] ?? null)
            ->setMarket($response['market_balance'] ?? null);

        $this->entityManager->persist($balance);
    }
}
