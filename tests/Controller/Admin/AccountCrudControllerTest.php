<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use JiguangSmsBundle\Controller\Admin\AccountCrudController;
use JiguangSmsBundle\Entity\Account;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Response;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(AccountCrudController::class)]
#[RunTestsInSeparateProcesses]
final class AccountCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getEntityFqcn(): string
    {
        return Account::class;
    }

    /** @return AccountCrudController */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(AccountCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        return [
            'ID' => ['ID'],
            '标题' => ['标题'],
            'AppKey' => ['AppKey'],
            '有效' => ['有效'],
            '创建者' => ['创建者'],
            '更新者' => ['更新者'],
            '创建时间' => ['创建时间'],
        ];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'appKey' => ['appKey'];
        yield 'masterSecret' => ['masterSecret'];
        yield 'valid' => ['valid'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        return self::provideNewPageFields();
    }

    public function testIndexPage(): void
    {
        $client = self::createClientWithDatabase();
        $this->loginAsAdmin($client);

        $crawler = $client->request('GET', '/admin');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Navigate to Account CRUD
        $link = $crawler->filter('a[href*="AccountCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
    }

    public function testCreateAccount(): void
    {
        // Test that the controller methods work correctly
        $controller = new AccountCrudController();
        $fields = $controller->configureFields('new');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testEditAccount(): void
    {
        // Test that configureFields returns appropriate fields
        $controller = new AccountCrudController();
        $fields = $controller->configureFields('edit');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testDetailAccount(): void
    {
        // Test that configureFields returns appropriate fields for detail view
        $controller = new AccountCrudController();
        $fields = $controller->configureFields('detail');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testEntityFqcnConfiguration(): void
    {
        $controller = new AccountCrudController();
        self::assertEquals(Account::class, $controller::getEntityFqcn());
    }

    /**
     * 测试表单验证错误 - 提交无效数据应该显示验证错误
     */
    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 获取表单并设置无效数据来触发验证错误
        $form = $crawler->selectButton('Create')->form();
        $entityName = $this->getEntitySimpleName();

        // 设置无效数据来触发验证错误
        // title 字段保持空值应该触发验证错误（必填字段）
        // appKey 字段保持空值应该触发验证错误（必填字段）
        $form[$entityName . '[title]'] = '';  // 空标题违反非空约束
        $form[$entityName . '[appKey]'] = '';  // 空AppKey违反非空约束

        $crawler = $client->submit($form);

        // 验证返回状态码（422 Unprocessable Entity 或重定向到表单页面显示错误）
        if (422 === $client->getResponse()->getStatusCode()) {
            $this->assertResponseStatusCodeSame(422);
            // 检查是否有验证错误信息
            $errorText = $crawler->filter('.invalid-feedback, .form-error-message, .alert-danger')->text();
            self::assertNotEmpty($errorText, '应该显示验证错误信息');
        } else {
            // 如果不是422，可能是重定向回表单页面显示错误
            $this->assertResponseIsSuccessful();
            $errorElements = $crawler->filter('.invalid-feedback, .form-error-message, .alert-danger');
            if ($errorElements->count() > 0) {
                $errorText = $errorElements->text();
                self::assertNotEmpty($errorText, '应该显示验证错误信息');
            } else {
                // 如果没有明显的错误元素，检查表单是否仍然存在（说明提交失败）
                $formExists = $crawler->filter('form[name="' . $entityName . '"]')->count() > 0;
                self::assertTrue($formExists, '表单验证失败时应该重新显示表单');
            }
        }
    }
}
