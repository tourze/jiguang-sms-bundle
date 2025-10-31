<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Repository\TextCodeRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(TextCodeRepository::class)]
#[RunTestsInSeparateProcesses]
final class TextCodeRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 不需要特殊初始化，repository 通过方法调用获取
    }

    private function getTextCodeRepository(): TextCodeRepository
    {
        return self::getService(TextCodeRepository::class);
    }

    public function testConstruct(): void
    {
        $repository = $this->getTextCodeRepository();
        $this->assertInstanceOf(TextCodeRepository::class, $repository);
        $this->assertSame(TextCode::class, $repository->getClassName());
    }

    public function testFindUnverifiedAndNotExpired(): void
    {
        $repository = $this->getTextCodeRepository();
        $result = $repository->findUnverifiedAndNotExpired();
        $this->assertIsArray($result);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template = $this->createTemplate($account, '验证码：{{code}}');
        $sign = $this->createSign($account, '测试签名');

        $textCode1 = $this->createTextCode($account, '13800138002', '123456', $template, $sign);
        $textCode2 = $this->createTextCode($account, '13800138001', '654321', $template, $sign);

        $this->persistEntities([$account, $template, $sign, $textCode1, $textCode2]);

        $repository = $this->getTextCodeRepository();
        $result = $repository->findOneBy([], ['mobile' => 'ASC']);
        $this->assertInstanceOf(TextCode::class, $result);
        $this->assertSame('13800138001', $result->getMobile());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template = $this->createTemplate($account, '验证码：{{code}}');
        $sign = $this->createSign($account, '测试签名');

        $this->persistEntities([$account, $template, $sign]);

        $textCode = $this->createTextCode($account, '13800138001', '123456', $template, $sign);

        $repository = $this->getTextCodeRepository();
        $repository->save($textCode, true);

        $found = $repository->find($textCode->getId());
        $this->assertInstanceOf(TextCode::class, $found);
        $this->assertSame('13800138001', $found->getMobile());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template = $this->createTemplate($account, '验证码：{{code}}');
        $sign = $this->createSign($account, '测试签名');

        $textCode = $this->createTextCode($account, '13800138001', '123456', $template, $sign);

        $this->persistEntities([$account, $template, $sign, $textCode]);

        $textCodeId = $textCode->getId();

        $repository = $this->getTextCodeRepository();
        $repository->remove($textCode, true);

        $found = $repository->find($textCodeId);
        $this->assertNull($found);
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $template1 = $this->createTemplate($account1, '验证码：{{code}}');
        $template2 = $this->createTemplate($account2, '验证码：{{code}}');

        $sign1 = $this->createSign($account1, '签名1');
        $sign2 = $this->createSign($account2, '签名2');

        $textCode1 = $this->createTextCode($account1, '13800138001', '123456', $template1, $sign1);
        $textCode2 = $this->createTextCode($account1, '13800138002', '654321', $template1, $sign1);
        $textCode3 = $this->createTextCode($account2, '13800138003', '789012', $template2, $sign2);

        $this->persistEntities([$account1, $account2, $template1, $template2, $sign1, $sign2, $textCode1, $textCode2, $textCode3]);

        $repository = $this->getTextCodeRepository();
        $count = $repository->count(['account' => $account1]);
        $this->assertSame(2, $count);
    }

    public function testFindOneByAssociationTemplateShouldReturnMatchingEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template1 = $this->createTemplate($account, '验证码：{{code}}');
        $template2 = $this->createTemplate($account, '通知消息：{{message}}');
        $sign = $this->createSign($account, '测试签名');

        $textCode1 = $this->createTextCode($account, '13800138001', '123456', $template1, $sign);
        $textCode2 = $this->createTextCode($account, '13800138002', '654321', $template2, $sign);

        $this->persistEntities([$account, $template1, $template2, $sign, $textCode1, $textCode2]);

        $repository = $this->getTextCodeRepository();
        $result = $repository->findOneBy(['template' => $template1]);
        $this->assertInstanceOf(TextCode::class, $result);
        $template = $result->getTemplate();
        $this->assertInstanceOf(Template::class, $template);
        $this->assertSame($template1->getId(), $template->getId());
    }

    protected function createNewEntity(): TextCode
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());
        $account->setValid(true);

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('验证码：{{code}}');
        $template->setType(TemplateTypeEnum::VERIFICATION);
        $template->setStatus(TemplateStatusEnum::APPROVED);

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setType(SignTypeEnum::COMPANY);
        $sign->setStatus(SignStatusEnum::APPROVED);
        $sign->setIsDefault(false);

        $textCode = new TextCode();
        $textCode->setAccount($account);
        $textCode->setMobile('1380013800' . rand(1, 9));
        $textCode->setCode(str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT));
        $textCode->setTtl(300);
        $textCode->setVerified(false);

        $textCode->setTemplate($template);
        $textCode->setSign($sign);

        return $textCode;
    }

    protected function getRepository(): TextCodeRepository
    {
        return $this->getTextCodeRepository();
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

    private function createTemplate(Account $account, string $templateContent): Template
    {
        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate($templateContent);
        $template->setType(TemplateTypeEnum::VERIFICATION);
        $template->setStatus(TemplateStatusEnum::APPROVED);

        return $template;
    }

    private function createSign(Account $account, string $signContent): Sign
    {
        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign($signContent);
        $sign->setType(SignTypeEnum::COMPANY);
        $sign->setStatus(SignStatusEnum::APPROVED);
        $sign->setIsDefault(false);

        return $sign;
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
        $this->getTextCodeRepository()->save($entity, false);

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

    private function createTextCode(Account $account, string $mobile, string $code, ?Template $template, ?Sign $sign): TextCode
    {
        $textCode = new TextCode();
        $textCode->setAccount($account);
        $textCode->setMobile($mobile);
        $textCode->setCode($code);
        $textCode->setTtl(300);
        $textCode->setVerified(false);

        $textCode->setTemplate($template);
        $textCode->setSign($sign);

        return $textCode;
    }
}
