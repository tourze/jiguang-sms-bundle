<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Repository\TextCodeRepository;
use PHPUnit\Framework\TestCase;

class TextCodeRepositoryTest extends TestCase
{
    private TextCodeRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new TextCodeRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(TextCodeRepository::class, $this->repository);
    }

}