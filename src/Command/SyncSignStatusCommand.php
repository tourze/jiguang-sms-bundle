<?php

namespace JiguangSmsBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Repository\SignRepository;
use JiguangSmsBundle\Service\SignService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'jiguang:sms:sync-sign-status',
    description: '同步极光短信签名状态',
)]
class SyncSignStatusCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SignService $signService,
        private readonly SignRepository $signRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $signs = $this->signRepository->findAll();

        foreach ($signs as $sign) {
            $sign->setSyncing(true);
            try {
                $this->signService->syncSignStatus($sign);
                $this->entityManager->flush();
                $io->success(sprintf('同步签名[%s]状态成功', $sign->getSign()));
            } catch (\Throwable $e) {
                $io->error(sprintf('同步签名[%s]状态失败: %s', $sign->getSign(), $e->getMessage()));
            }
        }

        return Command::SUCCESS;
    }
}
