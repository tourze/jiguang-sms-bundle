<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Repository\SignRepository;
use PHPUnit\Framework\TestCase;

class SignRepositoryTest extends TestCase
{
    private SignRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new SignRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(SignRepository::class, $this->repository);
    }
}