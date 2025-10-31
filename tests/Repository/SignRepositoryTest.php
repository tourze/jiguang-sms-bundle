<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Repository\SignRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(SignRepository::class)]
#[RunTestsInSeparateProcesses]
final class SignRepositoryTest extends AbstractRepositoryTestCase
{
    private SignRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(SignRepository::class);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account = $this->createAccount('Account', 'findone-order-key', 'secret');

        $sign1 = $this->createSign($account, '查询排序B', SignTypeEnum::COMPANY, SignStatusEnum::APPROVED);
        $sign2 = $this->createSign($account, '查询排序A', SignTypeEnum::APP, SignStatusEnum::PENDING);

        $this->persistEntities([$account, $sign1, $sign2]);

        // 使用account条件来限制查询范围，避免与fixtures冲突
        $result = $this->repository->findOneBy(['account' => $account], ['sign' => 'ASC']);
        $this->assertInstanceOf(Sign::class, $result);
        $this->assertSame('查询排序A', $result->getSign());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $this->persistEntities([$account]);

        $sign = $this->createSign($account, '新签名', SignTypeEnum::COMPANY, SignStatusEnum::APPROVED);

        $this->repository->save($sign, true);

        $found = $this->repository->find($sign->getId());
        $this->assertInstanceOf(Sign::class, $found);
        $this->assertSame('新签名', $found->getSign());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'remove-test-key', 'secret');
        $sign = $this->createSign($account, '删除测试签名', SignTypeEnum::COMPANY, SignStatusEnum::APPROVED);

        $this->persistEntities([$account, $sign]);

        $id = $sign->getId();

        $this->repository->remove($sign, true);

        $found = $this->repository->find($id);
        $this->assertNull($found);
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $sign1 = $this->createSign($account1, '签名1', SignTypeEnum::COMPANY, SignStatusEnum::APPROVED);
        $sign2 = $this->createSign($account1, '签名2', SignTypeEnum::APP, SignStatusEnum::PENDING);
        $sign3 = $this->createSign($account2, '签名3', SignTypeEnum::COMPANY, SignStatusEnum::REJECTED);

        $this->persistEntities([$account1, $account2, $sign1, $sign2, $sign3]);

        $count = $this->repository->count(['account' => $account1]);
        $this->assertSame(2, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $sign1 = $this->createSign($account1, '签名1', SignTypeEnum::COMPANY, SignStatusEnum::APPROVED);
        $sign2 = $this->createSign($account2, '签名2', SignTypeEnum::APP, SignStatusEnum::PENDING);

        $this->persistEntities([$account1, $account2, $sign1, $sign2]);

        $result = $this->repository->findOneBy(['account' => $account1]);
        $this->assertInstanceOf(Sign::class, $result);
        $this->assertSame($account1->getId(), $result->getAccount()->getId());
    }

    protected function createNewEntity(): object
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());
        $account->setValid(true);

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名_' . uniqid());
        $sign->setType(SignTypeEnum::COMPANY);
        $sign->setStatus(SignStatusEnum::APPROVED);
        $sign->setIsDefault(false);

        return $sign;
    }

    protected function getRepository(): SignRepository
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

    private function createSign(Account $account, string $signContent, SignTypeEnum $type, SignStatusEnum $status): Sign
    {
        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign($signContent);
        $sign->setType($type);
        $sign->setStatus($status);
        $sign->setIsDefault(false);

        return $sign;
    }
}
