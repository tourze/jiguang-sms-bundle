<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Repository\MessageRepository;
use PHPUnit\Framework\TestCase;

class MessageRepositoryTest extends TestCase
{
    private MessageRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new MessageRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(MessageRepository::class, $this->repository);
    }
}