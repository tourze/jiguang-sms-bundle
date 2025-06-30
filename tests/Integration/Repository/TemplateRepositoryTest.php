<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Repository\TemplateRepository;
use PHPUnit\Framework\TestCase;

class TemplateRepositoryTest extends TestCase
{
    private TemplateRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new TemplateRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(TemplateRepository::class, $this->repository);
    }
}