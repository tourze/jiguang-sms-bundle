<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Repository\AccountRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(AccountRepository::class)]
#[RunTestsInSeparateProcesses]
final class AccountRepositoryTest extends AbstractRepositoryTestCase
{
    private AccountRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AccountRepository::class);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account1 = $this->createAccount('AccountB', 'test-order-key1', 'secret1', true);
        $account2 = $this->createAccount('AccountA', 'test-order-key2', 'secret2', false);

        $this->persistEntities([$account1, $account2]);

        // 使用具体的appKey条件来限制查询范围
        $result = $this->repository->findOneBy(['appKey' => ['test-order-key1', 'test-order-key2']], ['title' => 'ASC']);
        $this->assertInstanceOf(Account::class, $result);
        $this->assertSame('AccountA', $result->getTitle());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret', true);

        $this->repository->save($account, true);

        $found = $this->repository->find($account->getId());
        $this->assertInstanceOf(Account::class, $found);
        $this->assertSame('Account', $found->getTitle());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'remove-test-key', 'secret', true);

        $this->persistEntities([$account]);

        $id = $account->getId();

        $this->repository->remove($account, true);

        $found = $this->repository->find($id);
        $this->assertNull($found);
    }

    protected function createNewEntity(): object
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());
        $account->setValid(true);

        return $account;
    }

    protected function getRepository(): AccountRepository
    {
        return $this->repository;
    }

    private function createAccount(string $title, string $appKey, string $masterSecret, ?bool $valid): Account
    {
        $account = new Account();
        $account->setTitle($title);
        $account->setAppKey($appKey);
        $account->setMasterSecret($masterSecret);
        $account->setValid($valid);

        return $account;
    }
}
