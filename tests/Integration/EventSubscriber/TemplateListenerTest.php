<?php

namespace JiguangSmsBundle\Tests\Integration\EventSubscriber;

use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\EventSubscriber\TemplateListener;
use JiguangSmsBundle\Service\TemplateService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class TemplateListenerTest extends TestCase
{
    private TemplateListener $listener;
    private TemplateService $templateService;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->templateService = $this->createMock(TemplateService::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->listener = new TemplateListener($this->templateService, $this->logger);
    }

    public function testPostPersistWithNewTemplate(): void
    {
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('isSyncing')
            ->willReturn(false);

        $template->expects($this->once())
            ->method('getTempId')
            ->willReturn(null);

        $this->templateService->expects($this->once())
            ->method('createRemoteTemplate')
            ->with($template);

        $this->listener->postPersist($template);
    }

    public function testPostPersistWithSyncingTemplate(): void
    {
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('isSyncing')
            ->willReturn(true);

        $this->templateService->expects($this->never())
            ->method('createRemoteTemplate');

        $this->listener->postPersist($template);
    }

    public function testPostUpdateWithExistingTemplate(): void
    {
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('isSyncing')
            ->willReturn(false);

        $template->expects($this->once())
            ->method('getTempId')
            ->willReturn(123);

        $this->templateService->expects($this->once())
            ->method('updateRemoteTemplate')
            ->with($template);

        $this->listener->postUpdate($template);
    }

    public function testPreRemoveWithExistingTemplate(): void
    {
        $template = $this->createMock(Template::class);
        $template->expects($this->once())
            ->method('isSyncing')
            ->willReturn(false);

        $template->expects($this->once())
            ->method('getTempId')
            ->willReturn(123);

        $this->templateService->expects($this->once())
            ->method('deleteRemoteTemplate')
            ->with($template);

        $this->listener->preRemove($template);
    }
}