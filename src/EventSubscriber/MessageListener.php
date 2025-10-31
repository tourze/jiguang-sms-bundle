<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Request\Message\SendMessageRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Message::class)]
class MessageListener
{
    public function __construct(
        private readonly JiguangSmsService $jiguangSmsService,
        #[Autowire(value: '%kernel.environment%')]
        private readonly string $environment,
    ) {
    }

    public function prePersist(Message $message): void
    {
        if (null !== $message->getMsgId()) {
            return;
        }

        $tempId = $message->getTemplate()->getTempId();
        if (null === $tempId) {
            return;
        }

        if ('test' === $this->environment) {
            $this->setTestResponse($message);

            return;
        }

        $this->sendMessage($message, $tempId);
    }

    private function setTestResponse(Message $message): void
    {
        $message->setMsgId('test_message_id_' . uniqid());
        $message->setResponse(['msg_id' => $message->getMsgId(), 'test' => true]);
    }

    private function sendMessage(Message $message, int $tempId): void
    {
        $request = $this->createRequest($message, $tempId);
        $response = $this->jiguangSmsService->request($request);
        $this->processResponse($message, $response);
    }

    private function createRequest(Message $message, int $tempId): SendMessageRequest
    {
        $request = new SendMessageRequest();
        $request->setAccount($message->getAccount());
        $request->setMobile($message->getMobile());
        $request->setTempId($tempId);

        if (null !== $message->getSign()) {
            $request->setSignId($message->getSign()->getSignId());
        }

        if (null !== $message->getTemplateParams()) {
            $request->setTempPara($message->getTemplateParams());
        }

        if (null !== $message->getTag()) {
            $request->setTag($message->getTag());
        }

        return $request;
    }

    private function processResponse(Message $message, mixed $response): void
    {
        $msgId = is_array($response) && isset($response['msg_id']) && is_string($response['msg_id']) ? $response['msg_id'] : null;
        $message->setMsgId($msgId);

        // 确保响应键都是字符串类型
        $stringKeyResponse = null;
        if (is_array($response)) {
            $stringKeyResponse = [];
            foreach ($response as $key => $value) {
                $stringKeyResponse[(string) $key] = $value;
            }
        }
        $message->setResponse($stringKeyResponse);
    }
}
