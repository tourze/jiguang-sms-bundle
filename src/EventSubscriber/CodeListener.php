<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Entity\VoiceCode;
use JiguangSmsBundle\Request\Code\SendTextCodeRequest;
use JiguangSmsBundle\Request\Code\SendVoiceCodeRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: TextCode::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist2', entity: VoiceCode::class)]
class CodeListener
{
    public function __construct(
        private readonly JiguangSmsService $jiguangSmsService,
        #[Autowire(value: '%kernel.environment%')]
        private readonly string $environment,
    ) {
    }

    public function prePersist(TextCode $code): void
    {
        if (null !== $code->getMsgId()) {
            return;
        }

        if ('test' === $this->environment) {
            $code->setMsgId('test_msg_id_' . uniqid());

            return;
        }

        $request = new SendTextCodeRequest();
        $request->setAccount($code->getAccount());
        $request->setMobile($code->getMobile());

        if (null !== $code->getTemplate()) {
            $request->setTempId($code->getTemplate()->getTempId());
        }

        if (null !== $code->getSign()) {
            $request->setSignId($code->getSign()->getSignId());
        }

        $response = $this->jiguangSmsService->request($request);
        $msgId = is_array($response) && isset($response['msg_id']) && is_string($response['msg_id']) ? $response['msg_id'] : null;
        $code->setMsgId($msgId);
    }

    public function prePersist2(VoiceCode $code): void
    {
        if (null !== $code->getMsgId()) {
            return;
        }

        if ('test' === $this->environment) {
            $code->setMsgId('test_voice_msg_id_' . uniqid());

            return;
        }

        $request = new SendVoiceCodeRequest();
        $request->setAccount($code->getAccount());
        $request->setMobile($code->getMobile());
        $request->setCode($code->getCode());
        $request->setTtl($code->getTtl());

        $response = $this->jiguangSmsService->request($request);
        $msgId = is_array($response) && isset($response['msg_id']) && is_string($response['msg_id']) ? $response['msg_id'] : null;
        $code->setMsgId($msgId);
    }
}
