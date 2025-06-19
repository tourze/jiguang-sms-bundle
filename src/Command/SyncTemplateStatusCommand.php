<?php

namespace JiguangSmsBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Repository\TemplateRepository;
use JiguangSmsBundle\Service\TemplateService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: self::NAME,
    description: '同步极光短信模板状态',
)]
class SyncTemplateStatusCommand extends Command
{
    public const NAME = 'jiguang:sms:sync-template-status';
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TemplateService $templateService,
        private readonly TemplateRepository $templateRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $templates = $this->templateRepository->findAll();

        foreach ($templates as $template) {
            $template->setSyncing(true);
            try {
                $this->templateService->syncTemplateStatus($template);
                $this->entityManager->flush();
                $io->success(sprintf('同步模板[%s]状态成功', $template->getTemplate()));
            } catch (\Throwable $e) {
                $io->error(sprintf('同步模板[%s]状态失败: %s', $template->getTemplate(), $e->getMessage()));
            }
        }

        return Command::SUCCESS;
    }
}
