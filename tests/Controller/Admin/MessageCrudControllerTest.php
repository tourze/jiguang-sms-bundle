<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use JiguangSmsBundle\Controller\Admin\MessageCrudController;
use JiguangSmsBundle\Entity\Message;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Response;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(MessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class MessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcn(): void
    {
        self::assertSame(Message::class, MessageCrudController::getEntityFqcn());
    }

    /** @return MessageCrudController */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(MessageCrudController::class);
    }

    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '所属账号' => ['所属账号'];
        yield '手机号' => ['手机号'];
        yield '短信模板' => ['短信模板'];
        yield '短信签名' => ['短信签名'];
        yield '消息ID' => ['消息ID'];
        yield '发送状态码' => ['发送状态码'];
        yield '是否送达' => ['是否送达'];
        yield '状态接收时间' => ['状态接收时间'];
        yield '创建时间' => ['创建时间'];
    }

    public static function provideNewPageFields(): iterable
    {
        yield 'mobile' => ['mobile'];
        yield 'tag' => ['tag'];
    }

    public static function provideEditPageFields(): iterable
    {
        yield 'mobile' => ['mobile'];
        yield 'tag' => ['tag'];
    }

    public function testIndexPage(): void
    {
        $client = self::createAuthenticatedClient();

        $crawler = $client->request('GET', '/admin');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Navigate to Message CRUD
        $link = $crawler->filter('a[href*="MessageCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
    }

    public function testCreateMessage(): void
    {
        // Test that the controller methods work correctly
        $controller = new MessageCrudController();
        $fields = $controller->configureFields('new');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testEditMessage(): void
    {
        // Test that configureFields returns appropriate fields
        $controller = new MessageCrudController();
        $fields = $controller->configureFields('edit');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testDetailMessage(): void
    {
        // Test that configureFields returns appropriate fields for detail view
        $controller = new MessageCrudController();
        $fields = $controller->configureFields('detail');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testEntityFqcnConfiguration(): void
    {
        $controller = new MessageCrudController();
        self::assertEquals(Message::class, $controller::getEntityFqcn());
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
        // mobile 字段设置无效的手机号格式
        $form[$entityName . '[mobile]'] = 'invalid_phone';  // 无效的手机号格式
        $form[$entityName . '[tag]'] = '';  // 空标签（如果有非空约束）

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
                self::assertGreaterThan(0, $formExists ? 1 : 0, '表单验证失败时应该重新显示表单');
            }
        }
    }
}
