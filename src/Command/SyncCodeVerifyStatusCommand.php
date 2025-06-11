<?php

namespace JiguangSmsBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Entity\AbstractCode;
use JiguangSmsBundle\Repository\TextCodeRepository;
use JiguangSmsBundle\Repository\VoiceCodeRepository;
use JiguangSmsBundle\Request\Code\VerifyCodeRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;

#[AsCronTask('* * * * *')]
#[AsCommand(
    name: 'jiguang:sms:sync-code-verify-status',
    description: '同步验证码验证状态',
)]
class SyncCodeVerifyStatusCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly JiguangSmsService $jiguangSmsService,
        private readonly TextCodeRepository $textCodeRepository,
        private readonly VoiceCodeRepository $voiceCodeRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('开始同步验证码验证状态...');

        // 获取所有未验证且未过期的验证码
        $unverifiedCodes = array_merge(
            $this->textCodeRepository->findUnverifiedAndNotExpired(),
            $this->voiceCodeRepository->findUnverifiedAndNotExpired()
        );

        $count = 0;
        foreach ($unverifiedCodes as $code) {
            try {
                $this->syncCodeStatus($code);
                $count++;
            } catch  (\Throwable $e) {
                $output->writeln(sprintf(
                    '<error>同步验证码[%s]状态失败: %s</error>',
                    $code->getMsgId(),
                    $e->getMessage()
                ));
            }
        }

        $this->entityManager->flush();
        $output->writeln(sprintf('同步完成,共处理 %d 个验证码', $count));

        return Command::SUCCESS;
    }

    private function syncCodeStatus(AbstractCode $code): void
    {
        $request = new VerifyCodeRequest();
        $request->setAccount($code->getAccount());
        $request->setMsgId($code->getMsgId())
            ->setCode($code->getCode());

        try {
            $this->jiguangSmsService->request($request);
            $code->setVerified(true);
        } catch  (\Throwable $e) {
            // 如果验证失败,说明验证码无效
            if ($e->getCode() === 50020) {
                $code->setVerified(false);
            } else {
                throw $e;
            }
        }
    }
}
