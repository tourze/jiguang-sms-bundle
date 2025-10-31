<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\AccountBalance;
use JiguangSmsBundle\Repository\AccountBalanceRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(AccountBalanceRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccountBalanceRepositoryTest extends AbstractRepositoryTestCase
{
    private AccountBalanceRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccountBalanceRepository::class);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $balance1 = $this->createAccountBalance($account1, 100, 50, 30, 20);
        $balance2 = $this->createAccountBalance($account2, 200, 100, 60, 40);

        $this->persistEntities([$account1, $account2, $balance1, $balance2]);

        $result = $this->repository->findOneBy(['account' => [$account1, $account2]], ['balance' => 'DESC']);
        $this->assertInstanceOf(AccountBalance::class, $result);
        $this->assertSame(200, $result->getBalance());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $this->persistEntities([$account]);

        $balance = $this->createAccountBalance($account, 100, 50, 30, 20);

        $this->repository->save($balance, true);

        $found = $this->repository->find($balance->getId());
        $this->assertInstanceOf(AccountBalance::class, $found);
        $this->assertSame(100, $found->getBalance());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $balance = $this->createAccountBalance($account, 100, 50, 30, 20);

        $this->persistEntities([$account, $balance]);
        $balanceId = $balance->getId();

        $this->repository->remove($balance, true);

        $found = $this->repository->find($balanceId);
        $this->assertNull($found);
    }

    public function testFindByAccountAssociation(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $balance1 = $this->createAccountBalance($account1, 100, 50, 30, 20);
        $balance2 = $this->createAccountBalance($account2, 200, 100, 60, 40);

        $this->persistEntities([$account1, $account2, $balance1, $balance2]);

        $results = $this->repository->findBy(['account' => $account1]);
        $this->assertCount(1, $results);
        $this->assertSame($account1->getId(), $results[0]->getAccount()->getId());
    }

    public function testFindByNullableFields(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $balance1 = $this->createAccountBalance($account1, null, 50, 30, 20);
        $balance2 = $this->createAccountBalance($account2, 200, null, 60, 40);

        $this->persistEntities([$account1, $account2, $balance1, $balance2]);

        $nullBalanceResults = $this->repository->findBy(['balance' => null]);
        $this->assertCount(1, $nullBalanceResults);
        $this->assertNull($nullBalanceResults[0]->getBalance());

        $nullVoiceResults = $this->repository->findBy(['voice' => null]);
        $this->assertCount(1, $nullVoiceResults);
        $this->assertNull($nullVoiceResults[0]->getVoice());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $balance1 = $this->createAccountBalance($account1, 100, 50, 30, 20);
        $balance2 = $this->createAccountBalance($account2, 300, 150, 90, 60);

        $this->persistEntities([$account1, $account2, $balance1, $balance2]);

        $count = $this->repository->count(['account' => $account1]);
        $this->assertSame(1, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $balance1 = $this->createAccountBalance($account1, 100, 50, 30, 20);
        $balance2 = $this->createAccountBalance($account2, 200, 100, 60, 40);

        $this->persistEntities([$account1, $account2, $balance1, $balance2]);

        $result = $this->repository->findOneBy(['account' => $account1]);
        $this->assertInstanceOf(AccountBalance::class, $result);
        $this->assertSame($account1->getId(), $result->getAccount()->getId());
    }

    protected function createNewEntity(): object
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());
        $account->setValid(true);

        $accountBalance = new AccountBalance();
        $accountBalance->setAccount($account);
        $accountBalance->setBalance(100);
        $accountBalance->setVoice(50);
        $accountBalance->setIndustry(30);
        $accountBalance->setMarket(20);

        return $accountBalance;
    }

    protected function getRepository(): AccountBalanceRepository
    {
        return $this->repository;
    }

    private function createAccount(string $title, string $appKey, string $masterSecret): Account
    {
        $account = new Account();
        $account->setTitle($title);
        $account->setAppKey($appKey);
        $account->setMasterSecret($masterSecret);
        $account->setValid(true);

        return $account;
    }

    private function createAccountBalance(Account $account, ?int $balance, ?int $voice, ?int $industry, ?int $market): AccountBalance
    {
        $accountBalance = new AccountBalance();
        $accountBalance->setAccount($account);
        $accountBalance->setBalance($balance);
        $accountBalance->setVoice($voice);
        $accountBalance->setIndustry($industry);
        $accountBalance->setMarket($market);

        return $accountBalance;
    }
}
