<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Service\SignService;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Sign::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Sign::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Sign::class)]
class SignListener
{
    public function __construct(
        private readonly SignService $signService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function postPersist(Sign $sign): void
    {
        if ($sign->isSyncing()) {
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

        try {
            // 更新签名时,同步到极光
            if ($sign->getSignId() !== null) {
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

        try {
            // 删除签名时,同步到极光
            if ($sign->getSignId() !== null) {
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
