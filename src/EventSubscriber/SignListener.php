<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Service\SignService;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Sign::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Sign::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Sign::class)]
#[WithMonologChannel(channel: 'jiguang_sms')]
readonly class SignListener
{
    public function __construct(
        private SignService $signService,
        private LoggerInterface $logger,
        #[Autowire(value: '%kernel.environment%')] private string $environment,
    ) {
    }

    public function postPersist(Sign $sign): void
    {
        if ($sign->isSyncing()) {
            return;
        }

        if ('test' === $this->environment) {
            if (null === $sign->getSignId()) {
                $sign->setSignId(999999);
            }

            return;
        }

        try {
            // 新建签名时,同步到极光
            if (null === $sign->getSignId()) {
                $this->signService->createRemoteSign($sign);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Create remote sign failed', [
                'exception' => $e,
                'sign' => $sign->getSign(),
            ]);
        }
    }

    public function postUpdate(Sign $sign): void
    {
        if ($sign->isSyncing()) {
            return;
        }

        if ('test' === $this->environment) {
            return;
        }

        try {
            // 更新签名时,同步到极光
            if (null !== $sign->getSignId()) {
                $this->signService->updateRemoteSign($sign);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Update remote sign failed', [
                'exception' => $e,
                'sign' => $sign->getSign(),
            ]);
        }
    }

    public function preRemove(Sign $sign): void
    {
        if ($sign->isSyncing()) {
            return;
        }

        if ('test' === $this->environment) {
            return;
        }

        try {
            // 删除签名时,同步到极光
            if (null !== $sign->getSignId()) {
                $this->signService->deleteRemoteSign($sign);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Delete remote sign failed', [
                'exception' => $e,
                'sign' => $sign->getSign(),
            ]);
        }
    }
}
