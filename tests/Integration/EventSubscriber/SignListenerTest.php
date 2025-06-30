<?php

namespace JiguangSmsBundle\Tests\Integration\EventSubscriber;

use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\EventSubscriber\SignListener;
use JiguangSmsBundle\Service\SignService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SignListenerTest extends TestCase
{
    private SignListener $listener;
    private SignService $signService;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->signService = $this->createMock(SignService::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->listener = new SignListener($this->signService, $this->logger);
    }

    public function testPostPersistWithNewSign(): void
    {
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('isSyncing')
            ->willReturn(false);

        $sign->expects($this->once())
            ->method('getSignId')
            ->willReturn(null);

        $this->signService->expects($this->once())
            ->method('createRemoteSign')
            ->with($sign);

        $this->listener->postPersist($sign);
    }

    public function testPostPersistWithSyncingSign(): void
    {
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('isSyncing')
            ->willReturn(true);

        $this->signService->expects($this->never())
            ->method('createRemoteSign');

        $this->listener->postPersist($sign);
    }

    public function testPostUpdateWithExistingSign(): void
    {
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('isSyncing')
            ->willReturn(false);

        $sign->expects($this->once())
            ->method('getSignId')
            ->willReturn(123);

        $this->signService->expects($this->once())
            ->method('updateRemoteSign')
            ->with($sign);

        $this->listener->postUpdate($sign);
    }

    public function testPreRemoveWithExistingSign(): void
    {
        $sign = $this->createMock(Sign::class);
        $sign->expects($this->once())
            ->method('isSyncing')
            ->willReturn(false);

        $sign->expects($this->once())
            ->method('getSignId')
            ->willReturn(123);

        $this->signService->expects($this->once())
            ->method('deleteRemoteSign')
            ->with($sign);

        $this->listener->preRemove($sign);
    }
}