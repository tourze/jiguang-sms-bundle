<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\AccountBalance;
use JiguangSmsBundle\Repository\AccountBalanceRepository;
use PHPUnit\Framework\TestCase;

class AccountBalanceRepositoryTest extends TestCase
{
    private AccountBalanceRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new AccountBalanceRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AccountBalanceRepository::class, $this->repository);
    }
}