<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Entity\VoiceCode;
use JiguangSmsBundle\Request\Code\SendTextCodeRequest;
use JiguangSmsBundle\Request\Code\SendVoiceCodeRequest;
use JiguangSmsBundle\Service\JiguangSmsService;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: TextCode::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist2', entity: VoiceCode::class)]
class CodeListener
{
    public function __construct(
        private readonly JiguangSmsService $jiguangSmsService,
    ) {
    }

    public function prePersist(TextCode $code): void
    {
        if ($code->getMsgId() !== null) {
            return;
        }

        $request = new SendTextCodeRequest();
        $request->setAccount($code->getAccount());
        $request->setMobile($code->getMobile());

        if ($code->getTemplate() !== null) {
            $request->setTempId($code->getTemplate()->getTempId());
        }

        if ($code->getSign() !== null) {
            $request->setSignId($code->getSign()->getSignId());
        }

        $response = $this->jiguangSmsService->request($request);
        $code->setMsgId($response['msg_id']);
    }

    public function prePersist2(VoiceCode $code): void
    {
        if ($code->getMsgId() !== null) {
            return;
        }

        $request = new SendVoiceCodeRequest();
        $request->setAccount($code->getAccount());
        $request
            ->setMobile($code->getMobile())
            ->setCode($code->getCode())
            ->setTtl($code->getTtl());

        $response = $this->jiguangSmsService->request($request);
        $code->setMsgId($response['msg_id']);
    }
}
