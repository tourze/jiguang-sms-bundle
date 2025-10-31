<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Repository\TemplateRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(TemplateRepository::class)]
#[RunTestsInSeparateProcesses]
final class TemplateRepositoryTest extends AbstractRepositoryTestCase
{
    private TemplateRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(TemplateRepository::class);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');

        $template1 = $this->createTemplate($account, 'B模板', TemplateTypeEnum::VERIFICATION, TemplateStatusEnum::APPROVED);
        $template2 = $this->createTemplate($account, 'A模板', TemplateTypeEnum::NOTIFICATION, TemplateStatusEnum::PENDING);

        $this->persistEntities([$account, $template1, $template2]);

        $result = $this->repository->findOneBy([], ['template' => 'ASC']);
        $this->assertInstanceOf(Template::class, $result);
        $this->assertSame('A模板', $result->getTemplate());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $this->persistEntities([$account]);

        $template = $this->createTemplate($account, '新模板', TemplateTypeEnum::VERIFICATION, TemplateStatusEnum::APPROVED);

        $this->repository->save($template, true);

        $found = $this->repository->find($template->getId());
        $this->assertInstanceOf(Template::class, $found);
        $this->assertSame('新模板', $found->getTemplate());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template = $this->createTemplate($account, '模板', TemplateTypeEnum::VERIFICATION, TemplateStatusEnum::APPROVED);

        $this->persistEntities([$account, $template]);

        $templateId = $template->getId();

        $this->repository->remove($template, true);

        $found = $this->repository->find($templateId);
        $this->assertNull($found);
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $template1 = $this->createTemplate($account1, '模板1', TemplateTypeEnum::VERIFICATION, TemplateStatusEnum::APPROVED);
        $template2 = $this->createTemplate($account1, '模板2', TemplateTypeEnum::NOTIFICATION, TemplateStatusEnum::PENDING);
        $template3 = $this->createTemplate($account2, '模板3', TemplateTypeEnum::MARKETING, TemplateStatusEnum::REJECTED);

        $this->persistEntities([$account1, $account2, $template1, $template2, $template3]);

        $count = $this->repository->count(['account' => $account1]);
        $this->assertSame(2, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account1 = $this->createAccount('Account1', 'key1', 'secret1');
        $account2 = $this->createAccount('Account2', 'key2', 'secret2');

        $template1 = $this->createTemplate($account1, '模板1', TemplateTypeEnum::VERIFICATION, TemplateStatusEnum::APPROVED);
        $template2 = $this->createTemplate($account2, '模板2', TemplateTypeEnum::NOTIFICATION, TemplateStatusEnum::PENDING);

        $this->persistEntities([$account1, $account2, $template1, $template2]);

        $result = $this->repository->findOneBy(['account' => $account1]);
        $this->assertInstanceOf(Template::class, $result);
        $this->assertSame($account1->getId(), $result->getAccount()->getId());
    }

    protected function createNewEntity(): object
    {
        $account = new Account();
        $account->setTitle('Test Account ' . uniqid());
        $account->setAppKey('test_app_key_' . uniqid());
        $account->setMasterSecret('test_master_secret_' . uniqid());
        $account->setValid(true);

        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate('验证码：{{code}}_' . uniqid());
        $template->setType(TemplateTypeEnum::VERIFICATION);
        $template->setStatus(TemplateStatusEnum::APPROVED);

        return $template;
    }

    protected function getRepository(): TemplateRepository
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

    private function createTemplate(Account $account, string $templateContent, TemplateTypeEnum $type, TemplateStatusEnum $status): Template
    {
        $template = new Template();
        $template->setAccount($account);
        $template->setTemplate($templateContent);
        $template->setType($type);
        $template->setStatus($status);

        return $template;
    }
}
