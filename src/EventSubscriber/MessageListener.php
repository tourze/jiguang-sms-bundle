<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Request\Message\SendMessageRequest;
use JiguangSmsBundle\Service\JiguangSmsService;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Message::class)]
class MessageListener
{
    public function __construct(
        private readonly JiguangSmsService $jiguangSmsService,
    ) {
    }

    public function prePersist(Message $message): void
    {
        if ($message->getMsgId() !== null) {
            return;
        }

        $request = new SendMessageRequest();
        $request->setAccount($message->getAccount());
        $request->setMobile($message->getMobile())
            ->setTempId($message->getTemplate()->getTempId());

        if ($message->getSign() !== null) {
            $request->setSignId($message->getSign()->getSignId());
        }

        if ($message->getTemplateParams() !== null) {
            $request->setTempPara($message->getTemplateParams());
        }

        if ($message->getTag() !== null) {
            $request->setTag($message->getTag());
        }

        $response = $this->jiguangSmsService->request($request);
        if (isset($response['msg_id'])) {
            $message->setMsgId($response['msg_id']);
        }
        $message->setResponse($response);
    }
}
