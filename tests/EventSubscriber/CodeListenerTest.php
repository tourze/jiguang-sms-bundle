<?php

namespace JiguangSmsBundle\Tests\EventSubscriber;

use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Entity\VoiceCode;
use JiguangSmsBundle\EventSubscriber\CodeListener;
use JiguangSmsBundle\Service\JiguangSmsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(CodeListener::class)]
final class CodeListenerTest extends TestCase
{
    /** @var MockObject&JiguangSmsService */
    private JiguangSmsService $jiguangSmsService;

    private CodeListener $listener;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var MockObject&JiguangSmsService $jiguangSmsService */
        $jiguangSmsService = $this->createMock(JiguangSmsService::class);
        $this->jiguangSmsService = $jiguangSmsService;
        $this->listener = new CodeListener($this->jiguangSmsService, 'test');
    }

    public function testPrePersistTextCode(): void
    {
        // 这里使用具体类 TextCode 而不是接口的原因：
        // 1. TextCode 是 Doctrine 实体类，没有对应的接口
        // 2. 在测试中需要模拟实体的特定方法（getMsgId, setMsgId）
        // 3. 使用 Mock 可以避免数据库依赖，保证测试速度和隔离性
        /** @var MockObject&TextCode $textCode */
        $textCode = $this->createMock(TextCode::class);
        $textCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn(null)
        ;

        // 在测试环境中，不会调用外部 API，而是直接设置测试用的 msgId
        $this->jiguangSmsService->expects($this->never())
            ->method('request')
        ;

        $textCode->expects($this->once())
            ->method('setMsgId')
            ->with(self::stringStartsWith('test_msg_id_'))
        ;

        $this->listener->prePersist($textCode);
    }

    public function testPrePersistTextCodeWithExistingMsgId(): void
    {
        // 这里使用具体类 TextCode 而不是接口的原因：
        // 1. TextCode 是 Doctrine 实体类，没有对应的接口
        // 2. 在测试中需要模拟实体的特定方法（getMsgId, setMsgId）
        // 3. 使用 Mock 可以避免数据库依赖，保证测试速度和隔离性
        /** @var MockObject&TextCode $textCode */
        $textCode = $this->createMock(TextCode::class);
        $textCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn('existing-msg-id')
        ;

        $this->jiguangSmsService->expects($this->never())
            ->method('request')
        ;

        $this->listener->prePersist($textCode);
    }

    public function testPrePersist2VoiceCode(): void
    {
        // 这里使用具体类 VoiceCode 而不是接口的原因：
        // 1. VoiceCode 是 Doctrine 实体类，没有对应的接口
        // 2. 在测试中需要模拟实体的特定方法（getMsgId, setMsgId）
        // 3. 使用 Mock 可以避免数据库依赖，保证测试速度和隔离性
        /** @var MockObject&VoiceCode $voiceCode */
        $voiceCode = $this->createMock(VoiceCode::class);
        $voiceCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn(null)
        ;

        // 在测试环境中，不会调用外部 API，而是直接设置测试用的 msgId
        $this->jiguangSmsService->expects($this->never())
            ->method('request')
        ;

        $voiceCode->expects($this->once())
            ->method('setMsgId')
            ->with(self::stringStartsWith('test_voice_msg_id_'))
        ;

        $this->listener->prePersist2($voiceCode);
    }

    public function testPrePersist2VoiceCodeWithExistingMsgId(): void
    {
        // 这里使用具体类 VoiceCode 而不是接口的原因：
        // 1. VoiceCode 是 Doctrine 实体类，没有对应的接口
        // 2. 在测试中需要模拟实体的特定方法（getMsgId, setMsgId）
        // 3. 使用 Mock 可以避免数据库依赖，保证测试速度和隔离性
        /** @var MockObject&VoiceCode $voiceCode */
        $voiceCode = $this->createMock(VoiceCode::class);
        $voiceCode->expects($this->once())
            ->method('getMsgId')
            ->willReturn('existing-voice-msg-id')
        ;

        $this->jiguangSmsService->expects($this->never())
            ->method('request')
        ;

        $this->listener->prePersist2($voiceCode);
    }
}
