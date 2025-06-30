<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Repository\AccountRepository;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new AccountRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AccountRepository::class, $this->repository);
    }
}