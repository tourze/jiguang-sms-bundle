<?php

namespace JiguangSmsBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Service\TemplateService;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Template::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Template::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Template::class)]
class TemplateListener
{
    public function __construct(
        private readonly TemplateService $templateService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function postPersist(Template $template): void
    {
        if ($template->isSyncing()) {
            return;
        }

        try {
            // 新建模板时,同步到极光
            if (null === $template->getTempId()) {
                $this->templateService->createRemoteTemplate($template);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Create remote template failed', [
                'exception' => $e,
                'template' => $template->getTemplate(),
            ]);
        }
    }

    public function postUpdate(Template $template): void
    {
        if ($template->isSyncing()) {
            return;
        }

        try {
            // 更新模板时,同步到极光
            if ($template->getTempId()) {
                $this->templateService->updateRemoteTemplate($template);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Update remote template failed', [
                'exception' => $e,
                'template' => $template->getTemplate(),
            ]);
        }
    }

    public function preRemove(Template $template): void
    {
        if ($template->isSyncing()) {
            return;
        }

        try {
            // 删除模板时,同步到极光
            if ($template->getTempId()) {
                $this->templateService->deleteRemoteTemplate($template);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Delete remote template failed', [
                'exception' => $e,
                'template' => $template->getTemplate(),
            ]);
        }
    }
}
