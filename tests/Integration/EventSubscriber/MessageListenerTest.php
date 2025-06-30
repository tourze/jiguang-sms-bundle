<?php

namespace JiguangSmsBundle\Tests\Integration\EventSubscriber;

use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\EventSubscriber\MessageListener;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\TestCase;

class MessageListenerTest extends TestCase
{
    private MessageListener $listener;
    private JiguangSmsService $jiguangSmsService;

    protected function setUp(): void
    {
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->listener = new MessageListener($this->jiguangSmsService);
    }

    public function testPrePersist(): void
    {
        $message = $this->createMock(Message::class);
        $template = $this->createMock(Template::class);

        $message->expects($this->once())
            ->method('getMsgId')
            ->willReturn(null);

        $message->expects($this->once())
            ->method('getTemplate')
            ->willReturn($template);

        $template->expects($this->once())
            ->method('getTempId')
            ->willReturn(123);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['msg_id' => 'test-msg-id']);

        $message->expects($this->once())
            ->method('setMsgId')
            ->with('test-msg-id');

        $message->expects($this->once())
            ->method('setResponse')
            ->with(['msg_id' => 'test-msg-id']);

        $this->listener->prePersist($message);
    }

    public function testPrePersistWithExistingMsgId(): void
    {
        $message = $this->createMock(Message::class);
        $message->expects($this->once())
            ->method('getMsgId')
            ->willReturn('existing-msg-id');

        $this->jiguangSmsService->expects($this->never())
            ->method('request');

        $this->listener->prePersist($message);
    }
}