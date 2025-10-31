<?php

namespace JiguangSmsBundle\Tests\Repository;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Repository\MessageRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(MessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class MessageRepositoryTest extends AbstractRepositoryTestCase
{
    private MessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(MessageRepository::class);
    }

    public function testFindOneByWithOrderByClauseShouldReturnFirstOrderedEntity(): void
    {
        $account = $this->createAccount('Account', 'find-order-key', 'secret');
        $template = $this->createTemplate($account, '验证码：{{code}}');
        $sign = $this->createSign($account, '测试签名');

        $message1 = $this->createMessage($account, '15800158002', $template, $sign);
        $message2 = $this->createMessage($account, '15800158001', $template, $sign);

        $this->persistEntities([$account, $template, $sign, $message1, $message2]);

        // 使用特定的account条件来限制查询范围
        $result = $this->repository->findOneBy(['account' => $account], ['mobile' => 'ASC']);
        $this->assertInstanceOf(Message::class, $result);
        $this->assertSame('15800158001', $result->getMobile());
    }

    public function testSaveMethodShouldPersistEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template = $this->createTemplate($account, '验证码：{{code}}');
        $sign = $this->createSign($account, '测试签名');

        $this->persistEntities([$account, $template, $sign]);

        $message = $this->createMessage($account, '13800138001', $template, $sign);

        $this->repository->save($message, true);

        $found = $this->repository->find($message->getId());
        $this->assertInstanceOf(Message::class, $found);
        $this->assertSame('13800138001', $found->getMobile());
    }

    public function testRemoveMethodShouldDeleteEntity(): void
    {
        $account = $this->createAccount('Account', 'remove-test-key', 'secret');
        $template = $this->createTemplate($account, '验证码：{{code}}');
        $sign = $this->createSign($account, '测试签名');

        $message = $this->createMessage($account, '15800158001', $template, $sign);

        $this->persistEntities([$account, $template, $sign, $message]);

        $id = $message->getId();

        $this->repository->remove($message, true);

        $found = $this->repository->find($id);
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

        $message1 = $this->createMessage($account1, '13800138001', $template1, $sign1);
        $message2 = $this->createMessage($account1, '13800138002', $template1, $sign1);
        $message3 = $this->createMessage($account2, '13800138003', $template2, $sign2);

        $this->persistEntities([$account1, $account2, $template1, $template2, $sign1, $sign2, $message1, $message2, $message3]);

        $count = $this->repository->count(['account' => $account1]);
        $this->assertSame(2, $count);
    }

    public function testFindOneByAssociationTemplateShouldReturnMatchingEntity(): void
    {
        $account = $this->createAccount('Account', 'key', 'secret');
        $template1 = $this->createTemplate($account, '验证码：{{code}}');
        $template2 = $this->createTemplate($account, '通知消息：{{message}}');
        $sign = $this->createSign($account, '测试签名');

        $message1 = $this->createMessage($account, '13800138001', $template1, $sign);
        $message2 = $this->createMessage($account, '13800138002', $template2, $sign);

        $this->persistEntities([$account, $template1, $template2, $sign, $message1, $message2]);

        $result = $this->repository->findOneBy(['template' => $template1]);
        $this->assertInstanceOf(Message::class, $result);
        $this->assertSame($template1->getId(), $result->getTemplate()->getId());
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
        $template->setTemplate('验证码：{{code}}');
        $template->setType(TemplateTypeEnum::VERIFICATION);
        $template->setStatus(TemplateStatusEnum::APPROVED);

        $sign = new Sign();
        $sign->setAccount($account);
        $sign->setSign('测试签名');
        $sign->setType(SignTypeEnum::COMPANY);
        $sign->setStatus(SignStatusEnum::APPROVED);
        $sign->setIsDefault(false);

        $message = new Message();
        $message->setAccount($account);
        $message->setMobile('1380013800' . rand(1, 9));
        $message->setTemplate($template);
        $message->setSign($sign);

        return $message;
    }

    protected function getRepository(): MessageRepository
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

    private function createMessage(Account $account, string $mobile, Template $template, ?Sign $sign): Message
    {
        $message = new Message();
        $message->setAccount($account);
        $message->setMobile($mobile);
        $message->setTemplate($template);
        $message->setSign($sign);

        return $message;
    }
}
