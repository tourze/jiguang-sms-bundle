<?php

namespace JiguangSmsBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Repository\AccountRepository;
use JiguangSmsBundle\Repository\MessageRepository;
use JiguangSmsBundle\Repository\TextCodeRepository;
use JiguangSmsBundle\Repository\VoiceCodeRepository;
use JiguangSmsBundle\Request\Message\GetStatusRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;

#[AsCronTask('*/5 * * * *')]
#[AsCommand(
    name: 'jiguang:sms:sync-message-status',
    description: '同步极光短信发送状态',
)]
class SyncMessageStatusCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AccountRepository $accountRepository,
        private readonly MessageRepository $messageRepository,
        private readonly TextCodeRepository $textCodeRepository,
        private readonly VoiceCodeRepository $voiceCodeRepository,
        private readonly JiguangSmsService $jiguangSmsService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->accountRepository->findAll();
        foreach ($accounts as $account) {
            $this->syncAccountStatus($account);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }

    private function syncAccountStatus(Account $account): void
    {
        $request = new GetStatusRequest();
        $request->setAccount($account);

        $response = $this->jiguangSmsService->request($request);
        if (!isset($response[0])) {
            return;
        }

        foreach ($response as $item) {
            $msgId = $item['msgId'];
            $status = $item['status'];
            $receiveTime = new \DateTimeImmutable('@' . ($item['receiveTime'] / 1000));

            // 同步普通短信状态
            $message = $this->messageRepository->findOneBy(['msgId' => $msgId]);
            if ($message !== null) {
                $message->setStatus($status)
                    ->setReceiveTime($receiveTime);
                continue;
            }

            // 同步文本验证码状态
            $textCode = $this->textCodeRepository->findOneBy(['msgId' => $msgId]);
            if ($textCode !== null) {
                $textCode->setStatus($status)
                    ->setReceiveTime($receiveTime);
                continue;
            }

            // 同步语音验证码状态
            $voiceCode = $this->voiceCodeRepository->findOneBy(['msgId' => $msgId]);
            if ($voiceCode !== null) {
                $voiceCode->setStatus($status)
                    ->setReceiveTime($receiveTime);
            }
        }
    }
}
