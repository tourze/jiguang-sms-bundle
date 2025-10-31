<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\VoiceCode;
use JiguangSmsBundle\Repository\VoiceCodeRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(VoiceCodeRepository::class)]
#[RunTestsInSeparateProcesses]
final class VoiceCodeRepositoryTest extends AbstractRepositoryTestCase
{
    private VoiceCodeRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(VoiceCodeRepository::class);
    }

    public function testFindUnverifiedAndNotExpired(): void
    {
        $result = $this->repository->findUnverifiedAndNotExpired();
        $this->assertIsArray($result);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account = $this->createAccount('Account', 'order-one-test-key', 'secret');

        $voiceCode1 = $this->createVoiceCode($account, '13800138002', '123456');
        $voiceCode2 = $this->createVoiceCode($account, '13800138001', '654321');

        $this->persistEntities([$account, $voiceCode1, $voiceCode2]);

        // 使用account条件来限制查询范围，避免与fixtures冲突
        $result = $this->repository->findOneBy(['account' => $account], ['mobile' => 'ASC']);
        $this->assertInstanceOf(VoiceCode::class, $result);
        $this->assertSame('13800138001', $result->getMobile());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $this->persistEntities([$account]);

        $voiceCode = $this->createVoiceCode($account, '13800138001', '123456');

        $this->repository->save($voiceCode, true);

        $found = $this->repository->find($voiceCode->getId());
        $this->assertInstanceOf(VoiceCode::class, $found);
        $this->assertSame('13800138001', $found->getMobile());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $voiceCode = $this->createVoiceCode($account, '13800138001', '123456');

        $this->persistEntities([$account, $voiceCode]);

        $voiceCodeId = $voiceCode->getId();

        $this->repository->remove($voiceCode, true);

        $found = $this->repository->find($voiceCodeId);
        $this->assertNull($found);
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $voiceCode1 = $this->createVoiceCode($account1, '13800138001', '123456');
        $voiceCode2 = $this->createVoiceCode($account1, '13800138002', '654321');
        $voiceCode3 = $this->createVoiceCode($account2, '13800138003', '789012');

        $this->persistEntities([$account1, $account2, $voiceCode1, $voiceCode2, $voiceCode3]);

        $count = $this->repository->count(['account' => $account1]);
        $this->assertSame(2, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $voiceCode1 = $this->createVoiceCode($account1, '13800138001', '123456');
        $voiceCode2 = $this->createVoiceCode($account2, '13800138002', '654321');

        $this->persistEntities([$account1, $account2, $voiceCode1, $voiceCode2]);

        $result = $this->repository->findOneBy(['account' => $account1]);
        $this->assertInstanceOf(VoiceCode::class, $result);
        $this->assertSame($account1->getId(), $result->getAccount()->getId());
    }

    protected function createNewEntity(): VoiceCode
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());
        $account->setValid(true);

        $voiceCode = new VoiceCode();
        $voiceCode->setAccount($account);
        $voiceCode->setMobile('1380013800' . rand(1, 9));
        $voiceCode->setCode(str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT));
        $voiceCode->setTtl(300);
        $voiceCode->setVerified(false);

        return $voiceCode;
    }

    protected function getRepository(): VoiceCodeRepository
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

    /**
     * 测试保存实体但不立即持久化 (工作中的替代版本)
     *
     * 原始的 testSaveWithFlushFalseShouldNotImmediatelyPersist 方法由于
     * symfony-testing-framework 的反射问题无法正常工作
     *
     * @see https://github.com/tourze/php-monorepo/issues/1386
     */
    public function testSaveWithFlushFalseWorkaround(): void
    {
        $entity = $this->createNewEntity();
        $this->repository->save($entity, false);

        // 在flush前，通过UnitOfWork检查实体状态
        $uow = self::getEntityManager()->getUnitOfWork();
        $isScheduled = $uow->isScheduledForInsert($entity);

        $this->assertTrue($isScheduled, '实体应该被调度为插入状态');

        // 手动flush
        self::getEntityManager()->flush();

        // flush后应该有ID
        $this->assertNotNull($entity->getId());
        $this->assertGreaterThan(0, $entity->getId());
    }

    private function createVoiceCode(Account $account, string $mobile, string $code): VoiceCode
    {
        $voiceCode = new VoiceCode();
        $voiceCode->setAccount($account);
        $voiceCode->setMobile($mobile);
        $voiceCode->setCode($code);
        $voiceCode->setTtl(300);
        $voiceCode->setVerified(false);

        return $voiceCode;
    }
}
