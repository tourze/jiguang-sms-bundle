# JiguangSmsBundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/jiguang-sms-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-sms-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/jiguang-sms-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-sms-bundle)
[![License](https://img.shields.io/packagist/l/tourze/jiguang-sms-bundle.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze-org/jiguang-sms-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze-org/jiguang-sms-bundle)
[![Build Status](https://img.shields.io/github/workflow/status/tourze-org/jiguang-sms-bundle/CI.svg?style=flat-square)](https://github.com/tourze-org/jiguang-sms-bundle/actions)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze-org/jiguang-sms-bundle.svg?style=flat-square)](https://codecov.io/gh/tourze-org/jiguang-sms-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/jiguang-sms-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-sms-bundle)

集成极光（JPush）短信服务的 Symfony Bundle，提供短信发送、签名管理、
模板管理和验证码功能。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
- [快速开始](#快速开始)
  - [基本短信发送](#基本短信发送)
  - [验证码功能](#验证码功能)
- [可用命令](#可用命令)
  - [账户余额同步](#账户余额同步)
  - [短信状态同步](#短信状态同步)
  - [验证码状态同步](#验证码状态同步)
  - [签名状态同步](#签名状态同步)
  - [模板状态同步](#模板状态同步)
- [高级用法](#高级用法)
  - [自定义请求处理器](#自定义请求处理器)
  - [事件监听器](#事件监听器)
- [安全](#安全)
  - [凭证管理](#凭证管理)
  - [速率限制](#速率限制)
  - [输入验证](#输入验证)
- [实体](#实体)
- [服务](#服务)
- [系统要求](#系统要求)
- [贡献](#贡献)
- [许可证](#许可证)

## 功能特性

- 📱 支持模板的短信发送
- 🔐 文本和语音验证码生成
- 📋 短信模板管理与状态同步
- ✏️ 短信签名管理与状态跟踪
- 💰 账户余额监控
- 📊 短信发送状态跟踪
- ⚡ 通过定时任务自动同步
- 🎯 完整的 Doctrine ORM 集成

## 安装

```bash
composer require tourze/jiguang-sms-bundle
```

## 配置

在 Symfony 应用程序中配置极光短信凭证：

```yaml
# config/packages/jiguang_sms.yaml
jiguang_sms:
    accounts:
        default:
            app_key: "your_app_key"
            master_secret: "your_master_secret"
```

### 数据库设置

该 Bundle 需要数据库表来存储短信数据。运行以下命令创建它们：

```bash
# 生成迁移文件
php bin/console doctrine:migrations:diff

# 应用迁移
php bin/console doctrine:migrations:migrate
```

### Bundle 注册

如果您没有使用 Symfony Flex，请在 `config/bundles.php` 中手动注册 Bundle：

```php
<?php

return [
    // ... 其他 bundles
    JiguangSmsBundle\JiguangSmsBundle::class => ['all' => true],
];
```

## 快速开始

### 基本短信发送

```php
<?php

use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Request\Message\SendMessageRequest;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Repository\AccountRepository;

// 注入服务
public function __construct(
    private JiguangSmsService $jiguangSmsService,
    private AccountRepository $accountRepository
) {}

public function sendSms(): void
{
    $account = $this->accountRepository->findOneBy(['appKey' => 'your_app_key']);
    
    $request = new SendMessageRequest();
    $request->setAccount($account)
        ->setMobile('13800138000')
        ->setTempId('your_template_id')
        ->setTempParas(['code' => '123456']);
    
    $response = $this->jiguangSmsService->request($request);
    
    // 响应包含用于跟踪的 msg_id
    $msgId = $response['msg_id'];
}
```

### 验证码功能

```php
use JiguangSmsBundle\Request\Code\SendTextCodeRequest;
use JiguangSmsBundle\Request\Code\SendVoiceCodeRequest;
use JiguangSmsBundle\Request\Code\VerifyCodeRequest;

// 发送文本验证码
$request = new SendTextCodeRequest();
$request->setAccount($account)
    ->setMobile('13800138000')
    ->setTempId('your_template_id');

$response = $this->jiguangSmsService->request($request);

// 发送语音验证码
$voiceRequest = new SendVoiceCodeRequest();
$voiceRequest->setAccount($account)
    ->setMobile('13800138000')
    ->setTtl(300); // 5 分钟

$voiceResponse = $this->jiguangSmsService->request($voiceRequest);

// 验证验证码
$verifyRequest = new VerifyCodeRequest();
$verifyRequest->setAccount($account)
    ->setMsgId($response['msg_id'])
    ->setCode('user_input_code');

$verifyResponse = $this->jiguangSmsService->request($verifyRequest);
$isValid = $verifyResponse['is_valid'] ?? false;
```

## 可用命令

该 Bundle 提供多个控制台命令用于自动同步：

### 账户余额同步
```bash
php bin/console jiguang:sms:sync-account-balance
```
从极光服务器同步账户余额信息。通过定时任务每 10 分钟自动运行。

### 短信状态同步
```bash
php bin/console jiguang:sms:sync-message-status
```
更新已发送短信的送达状态。通过定时任务每 5 分钟自动运行。

### 验证码状态同步
```bash
php bin/console jiguang:sms:sync-code-verify-status
```
检查并更新活跃验证码的验证状态。通过定时任务每分钟自动运行。

### 签名状态同步
```bash
php bin/console jiguang:sms:sync-sign-status
```
与极光服务器同步签名审核状态。根据需要手动运行。

### 模板状态同步
```bash
php bin/console jiguang:sms:sync-template-status
```
从极光服务器更新模板审核状态。根据需要手动运行。

> **注意**：定时任务使用 `tourze/symfony-cron-job-bundle` 自动配置。确保您的应用程序已正确设置定时任务调度。

## 高级用法

### 模板和签名管理

该 Bundle 提供用于管理短信模板和签名的服务：

```php
use JiguangSmsBundle\Service\TemplateService;
use JiguangSmsBundle\Service\SignService;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Entity\Sign;

class SmsManagementService
{
    public function __construct(
        private TemplateService $templateService,
        private SignService $signService
    ) {}
    
    public function createTemplate(Template $template): void
    {
        // 远程创建模板并同步本地 ID
        $this->templateService->createRemoteTemplate($template);
        
        // 定期同步状态
        $this->templateService->syncTemplateStatus($template);
    }
    
    public function createSignature(Sign $sign): void
    {
        // 远程创建签名并同步本地 ID
        $this->signService->createRemoteSign($sign);
        
        // 定期同步状态
        $this->signService->syncSignStatus($sign);
    }
}
```

### 自定义请求处理器

您可以为专业的短信操作创建自定义请求处理器：

```php
use JiguangSmsBundle\Request\Message\SendMessageRequest;
use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Entity\Account;

class CustomSmsHandler
{
    public function __construct(private JiguangSmsService $smsService) {}
    
    public function sendBulkMessages(Account $account, array $recipients, string $templateId, array $params = []): array
    {
        $results = [];
        foreach ($recipients as $mobile) {
            $request = new SendMessageRequest();
            $request->setAccount($account)
                ->setMobile($mobile)
                ->setTempId($templateId)
                ->setTempParas($params);
            $results[] = $this->smsService->request($request);
        }
        return $results;
    }
}
```

### 事件监听器

该 Bundle 提供用于自动处理的事件订阅器：

```php
// 内置事件订阅器：
// - CodeListener: 处理验证码事件
// - MessageListener: 处理短信消息事件
// - SignListener: 处理签名事件
// - TemplateListener: 处理模板事件
```

### 错误处理

该 Bundle 提供特定的异常类型用于错误处理：

```php
use JiguangSmsBundle\Exception\JiguangSmsException;
use JiguangSmsBundle\Exception\InvalidSignStatusException;
use JiguangSmsBundle\Exception\InvalidTemplateStatusException;

try {
    $this->jiguangSmsService->request($request);
} catch (InvalidSignStatusException $e) {
    // 处理无效签名状态
} catch (InvalidTemplateStatusException $e) {
    // 处理无效模板状态
} catch (JiguangSmsException $e) {
    // 处理通用极光短信错误
}
```

## 安全

### 凭证管理

- 使用环境变量安全地存储您的极光短信凭证
- 永远不要将凭证提交到版本控制
- 在生产环境中使用 Symfony 的秘密管理

```yaml
# config/packages/jiguang_sms.yaml
jiguang_sms:
    accounts:
        default:
            app_key: '%env(JIGUANG_APP_KEY)%'
            master_secret: '%env(JIGUANG_MASTER_SECRET)%'
```

### 速率限制

实施速率限制以防止滥用：

```php
use Symfony\Component\RateLimiter\RateLimiterFactory;

class SmsController
{
    public function __construct(
        private RateLimiterFactory $smsLimiter,
        private JiguangSmsService $smsService
    ) {}
    
    public function sendVerificationCode(Request $request): Response
    {
        $limiter = $this->smsLimiter->create($request->getClientIp());
        if (!$limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
        
        // 发送短信...
    }
}
```

### 输入验证

始终验证手机号码和消息内容：

```php
use Symfony\Component\Validator\Constraints as Assert;

class SmsRequest
{
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^1[3-9]\\d{9}$/', message: '无效的手机号码')]
    private string $mobile;
    
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private string $content;
}
```

## 实体

该 Bundle 提供以下 Doctrine 实体：

### 核心实体
- **`Account`** - 极光短信账户凭证和配置
- **`Message`** - 短信记录及送达状态
- **`TextCode`** / **`VoiceCode`** - 验证码记录及验证状态
- **`Sign`** - 短信签名管理及审核状态
- **`Template`** - 短信模板管理及审核状态
- **`AccountBalance`** - 不同短信类型的账户余额跟踪

### 实体关系
```text
Account (1) -----> (N) Message
Account (1) -----> (N) TextCode/VoiceCode
Account (1) -----> (N) Sign
Account (1) -----> (N) Template
Account (1) -----> (1) AccountBalance
```

### 实体特性
- **时间戳**：自动创建和更新时间戳
- **责任追踪**：自动用户跟踪创建/更新操作
- **变更跟踪**：字段级变更跟踪
- **索引优化**：为性能优化的数据库索引

## 服务

### 核心服务
- **`JiguangSmsService`** - 极光短信通信的主 API 客户端
- **`SignService`** - 签名管理操作（创建、更新、删除、同步）
- **`TemplateService`** - 模板管理操作（创建、更新、删除、同步）

### 仓库服务
- **`AccountRepository`** - 账户实体仓库
- **`MessageRepository`** - 消息实体仓库
- **`TextCodeRepository`** / **`VoiceCodeRepository`** - 验证码仓库
- **`SignRepository`** - 签名实体仓库
- **`TemplateRepository`** - 模板实体仓库
- **`AccountBalanceRepository`** - 账户余额仓库

### 服务特性
- 通过 Symfony DI 自动依赖注入
- 具有特定异常类型的错误处理
- 网络操作的内置重试机制
- 全面的日志记录支持

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- ext-mbstring（用于多字节字符串处理）

## 故障排除

### 常见问题

**1. "账户未找到" 错误**
```php
// 确保您的账户存在于数据库中
$account = $this->accountRepository->findOneBy(['appKey' => 'your_app_key']);
if (!$account) {
    throw new \Exception('账户未找到');
}
```

**2. "签名无效" 错误**
```php
// 检查您的 app_key 和 master_secret 是否正确
// 验证账户是否标记为有效
$account->setValid(true);
$this->entityManager->flush();
```

**3. 数据库连接问题**
```bash
# 确保已运行迁移
php bin/console doctrine:migrations:migrate

# 检查表是否存在
php bin/console doctrine:schema:validate
```

**4. 定时任务未运行**
```bash
# 检查定时任务是否已注册
php bin/console cron:list

# 手动运行同步命令
php bin/console jiguang:sms:sync-account-balance
```

### 调试

启用调试模式以查看详细的 API 请求：

```yaml
# config/packages/monolog.yaml
monolog:
    handlers:
        main:
            type: stream
            path: "%kernel.logs_dir%/%kernel.environment%.log"
            level: debug
            channels: ["!event"]
```

### 性能优化

对于大量短信发送，请考虑：

1. **批处理**：使用队列进行批量短信操作
2. **连接池**：配置 HTTP 客户端使用连接池
3. **缓存**：缓存频繁访问的账户数据
4. **速率限制**：实施速率限制以避免 API 限制

```yaml
# config/packages/framework.yaml
framework:
    http_client:
        default_options:
            max_duration: 10
            max_redirects: 3
        scoped_clients:
            jiguang_sms:
                base_uri: 'https://api.sms.jpush.cn'
                timeout: 30
```

## 贡献

请查看 [CONTRIBUTING.md](CONTRIBUTING.md) 了解详情。

## 许可证

MIT 许可证。请查看 [许可证文件](LICENSE) 获取更多信息。
