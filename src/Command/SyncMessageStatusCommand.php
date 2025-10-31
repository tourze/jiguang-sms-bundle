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

#[AsCronTask(expression: '*/5 * * * *')]
#[AsCommand(
    name: self::NAME,
    description: '同步极光短信发送状态',
)]
class SyncMessageStatusCommand extends Command
{
    public const NAME = 'jiguang:sms:sync-message-status';

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
        if (!is_array($response) || !isset($response[0])) {
            return;
        }

        foreach ($response as $item) {
            $this->processStatusItem($item);
        }
    }

    private function processStatusItem(mixed $item): void
    {
        if (!is_array($item)) {
            return;
        }

        $msgId = $item['msgId'] ?? null;
        $status = $item['status'] ?? null;
        $receiveTime = isset($item['receiveTime']) && is_numeric($item['receiveTime'])
            ? new \DateTimeImmutable('@' . (intval($item['receiveTime']) / 1000))
            : null;

        if (null === $msgId || null === $status || null === $receiveTime) {
            return;
        }

        $this->updateEntityStatus($msgId, $status, $receiveTime);
    }

    private function updateEntityStatus(mixed $msgId, mixed $status, \DateTimeImmutable $receiveTime): void
    {
        $intStatus = is_int($status) ? $status : null;

        // 同步普通短信状态
        $message = $this->messageRepository->findOneBy(['msgId' => $msgId]);
        if (null !== $message) {
            $message->setStatus($intStatus);
            $message->setReceiveTime($receiveTime);

            return;
        }

        // 同步文本验证码状态
        $textCode = $this->textCodeRepository->findOneBy(['msgId' => $msgId]);
        if (null !== $textCode) {
            $textCode->setStatus($intStatus);
            $textCode->setReceiveTime($receiveTime);

            return;
        }

        // 同步语音验证码状态
        $voiceCode = $this->voiceCodeRepository->findOneBy(['msgId' => $msgId]);
        if (null !== $voiceCode) {
            $voiceCode->setStatus($intStatus);
            $voiceCode->setReceiveTime($receiveTime);
        }
    }
}
