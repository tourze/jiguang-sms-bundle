<?php

namespace JiguangSmsBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use JiguangSmsBundle\Command\SyncTemplateStatusCommand;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Repository\TemplateRepository;
use JiguangSmsBundle\Service\TemplateService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncTemplateStatusCommandTest extends TestCase
{
    private SyncTemplateStatusCommand $command;
    private EntityManagerInterface $entityManager;
    private TemplateService $templateService;
    private TemplateRepository $templateRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->templateService = $this->createMock(TemplateService::class);
        $this->templateRepository = $this->createMock(TemplateRepository::class);

        $this->command = new SyncTemplateStatusCommand(
            $this->entityManager,
            $this->templateService,
            $this->templateRepository
        );
    }

    public function testExecute(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $template = $this->createMock(Template::class);
        $this->templateRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$template]);

        $template->expects($this->once())
            ->method('setSyncing')
            ->with(true);

        $template->expects($this->once())
            ->method('getTemplate')
            ->willReturn('test-template');

        $this->templateService->expects($this->once())
            ->method('syncTemplateStatus')
            ->with($template);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->command->run($input, $output);

        $this->assertEquals(0, $result);
    }
}