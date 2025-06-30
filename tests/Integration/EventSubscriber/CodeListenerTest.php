<?php

namespace JiguangSmsBundle\Tests\Integration\EventSubscriber;

use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Entity\VoiceCode;
use JiguangSmsBundle\EventSubscriber\CodeListener;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\TestCase;

class CodeListenerTest extends TestCase
{
    private CodeListener $listener;
    private JiguangSmsService $jiguangSmsService;

    protected function setUp(): void
    {
        $this->jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->listener = new CodeListener($this->jiguangSmsService);
    }

    public function testPrePersistTextCode(): void
    {
        $textCode = $this->createMock(TextCode::class);
        $textCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn(null);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['msg_id' => 'test-msg-id']);

        $textCode->expects($this->once())
            ->method('setMsgId')
            ->with('test-msg-id');

        $this->listener->prePersist($textCode);
    }

    public function testPrePersistTextCodeWithExistingMsgId(): void
    {
        $textCode = $this->createMock(TextCode::class);
        $textCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn('existing-msg-id');

        $this->jiguangSmsService->expects($this->never())
            ->method('request');

        $this->listener->prePersist($textCode);
    }

    public function testPrePersist2VoiceCode(): void
    {
        $voiceCode = $this->createMock(VoiceCode::class);
        $voiceCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn(null);

        $this->jiguangSmsService->expects($this->once())
            ->method('request')
            ->willReturn(['msg_id' => 'test-voice-msg-id']);

        $voiceCode->expects($this->once())
            ->method('setMsgId')
            ->with('test-voice-msg-id');

        $this->listener->prePersist2($voiceCode);
    }

    public function testPrePersist2VoiceCodeWithExistingMsgId(): void
    {
        $voiceCode = $this->createMock(VoiceCode::class);
        $voiceCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn('existing-voice-msg-id');

        $this->jiguangSmsService->expects($this->never())
            ->method('request');

        $this->listener->prePersist2($voiceCode);
    }
}