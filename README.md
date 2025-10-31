# JiguangSmsBundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/jiguang-sms-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-sms-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/jiguang-sms-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-sms-bundle)
[![License](https://img.shields.io/packagist/l/tourze/jiguang-sms-bundle.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze-org/jiguang-sms-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze-org/jiguang-sms-bundle)
[![Build Status](https://img.shields.io/github/workflow/status/tourze-org/jiguang-sms-bundle/CI.svg?style=flat-square)](https://github.com/tourze-org/jiguang-sms-bundle/actions)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze-org/jiguang-sms-bundle.svg?style=flat-square)](https://codecov.io/gh/tourze-org/jiguang-sms-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/jiguang-sms-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/jiguang-sms-bundle)

A Symfony bundle that integrates Jiguang (JPush) SMS service, providing SMS sending, signature management, 
template management, and verification code capabilities.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
  - [Basic SMS Sending](#basic-sms-sending)
  - [Verification Code](#verification-code)
- [Available Commands](#available-commands)
  - [Account Balance Synchronization](#account-balance-synchronization)
  - [Message Status Synchronization](#message-status-synchronization)
  - [Verification Code Status Synchronization](#verification-code-status-synchronization)
  - [Signature Status Synchronization](#signature-status-synchronization)
  - [Template Status Synchronization](#template-status-synchronization)
- [Advanced Usage](#advanced-usage)
  - [Custom Request Handlers](#custom-request-handlers)
  - [Event Listeners](#event-listeners)
- [Security](#security)
  - [Credentials Management](#credentials-management)
  - [Rate Limiting](#rate-limiting)
  - [Input Validation](#input-validation)
- [Entities](#entities)
- [Services](#services)
- [Requirements](#requirements)
- [Contributing](#contributing)
- [License](#license)

## Features

- 📱 SMS message sending with template support
- 🔐 Text and voice verification code generation
- 📋 SMS template management with status synchronization
- ✏️ SMS signature management with status tracking
- 💰 Account balance monitoring
- 📊 Message delivery status tracking
- ⚡ Automated synchronization via cron jobs
- 🎯 Full Doctrine ORM integration

## Installation

```bash
composer require tourze/jiguang-sms-bundle
```

## Configuration

Configure your Jiguang SMS credentials in your Symfony application:

```yaml
# config/packages/jiguang_sms.yaml
jiguang_sms:
    accounts:
        default:
            app_key: "your_app_key"
            master_secret: "your_master_secret"
```

### Database Setup

The bundle requires database tables to store SMS data. Run the following commands to create them:

```bash
# Generate migration files
php bin/console doctrine:migrations:diff

# Apply migrations
php bin/console doctrine:migrations:migrate
```

### Bundle Registration

If you're not using Symfony Flex, manually register the bundle in `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    JiguangSmsBundle\JiguangSmsBundle::class => ['all' => true],
];
```

## Quick Start

### Basic SMS Sending

```php
<?php

use JiguangSmsBundle\Service\JiguangSmsService;
use JiguangSmsBundle\Request\Message\SendMessageRequest;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Repository\AccountRepository;

// Inject the service
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
    
    // Response contains msg_id for tracking
    $msgId = $response['msg_id'];
}
```

### Verification Code

```php
use JiguangSmsBundle\Request\Code\SendTextCodeRequest;
use JiguangSmsBundle\Request\Code\SendVoiceCodeRequest;
use JiguangSmsBundle\Request\Code\VerifyCodeRequest;

// Send text verification code
$request = new SendTextCodeRequest();
$request->setAccount($account)
    ->setMobile('13800138000')
    ->setTempId('your_template_id');

$response = $this->jiguangSmsService->request($request);

// Send voice verification code
$voiceRequest = new SendVoiceCodeRequest();
$voiceRequest->setAccount($account)
    ->setMobile('13800138000')
    ->setTtl(300); // 5 minutes

$voiceResponse = $this->jiguangSmsService->request($voiceRequest);

// Verify code
$verifyRequest = new VerifyCodeRequest();
$verifyRequest->setAccount($account)
    ->setMsgId($response['msg_id'])
    ->setCode('user_input_code');

$verifyResponse = $this->jiguangSmsService->request($verifyRequest);
$isValid = $verifyResponse['is_valid'] ?? false;
```

## Available Commands

The bundle provides several console commands for automated synchronization:

### Account Balance Synchronization
```bash
php bin/console jiguang:sms:sync-account-balance
```
Synchronizes account balance information from Jiguang servers. 
Runs automatically every 10 minutes via cron.

### Message Status Synchronization  
```bash
php bin/console jiguang:sms:sync-message-status
```
Updates delivery status for sent SMS messages. 
Runs automatically every 5 minutes via cron.

### Verification Code Status Synchronization
```bash
php bin/console jiguang:sms:sync-code-verify-status
```
Checks and updates verification status for active verification codes. 
Runs automatically every minute via cron.

### Signature Status Synchronization
```bash
php bin/console jiguang:sms:sync-sign-status
```
Synchronizes signature approval status with Jiguang servers. Run manually as needed.

### Template Status Synchronization
```bash
php bin/console jiguang:sms:sync-template-status
```
Updates template approval status from Jiguang servers. Run manually as needed.

> **Note**: The cron jobs are automatically configured using the `tourze/symfony-cron-job-bundle`. Make sure your application has proper cron job scheduling set up.

## Advanced Usage

### Template and Signature Management

The bundle provides services for managing SMS templates and signatures:

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
        // Create template remotely and sync local ID
        $this->templateService->createRemoteTemplate($template);
        
        // Sync status periodically
        $this->templateService->syncTemplateStatus($template);
    }
    
    public function createSignature(Sign $sign): void
    {
        // Create signature remotely and sync local ID
        $this->signService->createRemoteSign($sign);
        
        // Sync status periodically
        $this->signService->syncSignStatus($sign);
    }
}
```

### Custom Request Handlers

You can create custom request handlers for specialized SMS operations:

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

### Event Listeners

The bundle provides event subscribers for automatic processing:

```php
// Built-in event subscribers:
// - CodeListener: Handles verification code events
// - MessageListener: Handles SMS message events
// - SignListener: Handles signature events
// - TemplateListener: Handles template events
```

### Error Handling

The bundle provides specific exception types for error handling:

```php
use JiguangSmsBundle\Exception\JiguangSmsException;
use JiguangSmsBundle\Exception\InvalidSignStatusException;
use JiguangSmsBundle\Exception\InvalidTemplateStatusException;

try {
    $this->jiguangSmsService->request($request);
} catch (InvalidSignStatusException $e) {
    // Handle invalid signature status
} catch (InvalidTemplateStatusException $e) {
    // Handle invalid template status
} catch (JiguangSmsException $e) {
    // Handle general Jiguang SMS errors
}
```

## Security

### Credentials Management

- Store your Jiguang SMS credentials securely using environment variables
- Never commit credentials to version control
- Use Symfony's secrets management for production environments

```yaml
# config/packages/jiguang_sms.yaml
jiguang_sms:
    accounts:
        default:
            app_key: '%env(JIGUANG_APP_KEY)%'
            master_secret: '%env(JIGUANG_MASTER_SECRET)%'
```

### Rate Limiting

Implement rate limiting to prevent abuse:

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
        
        // Send SMS...
    }
}
```

### Input Validation

Always validate phone numbers and message content:

```php
use Symfony\Component\Validator\Constraints as Assert;

class SmsRequest
{
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^1[3-9]\d{9}$/', message: 'Invalid mobile number')]
    private string $mobile;
    
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private string $content;
}
```

## Entities

The bundle provides the following Doctrine entities:

### Core Entities
- **`Account`** - Jiguang SMS account credentials and configuration
- **`Message`** - SMS message records with delivery status
- **`TextCode`** / **`VoiceCode`** - Verification code records with verification status
- **`Sign`** - SMS signature management with approval status
- **`Template`** - SMS template management with approval status
- **`AccountBalance`** - Account balance tracking for different SMS types

### Entity Relationships
```text
Account (1) -----> (N) Message
Account (1) -----> (N) TextCode/VoiceCode
Account (1) -----> (N) Sign
Account (1) -----> (N) Template
Account (1) -----> (1) AccountBalance
```

### Entity Features
- **Timestampable**: Automatic created_at and updated_at timestamps
- **Blameable**: Automatic user tracking for create/update operations
- **Trackable**: Field-level change tracking
- **Indexed**: Optimized database indexes for performance

## Services

### Core Services
- **`JiguangSmsService`** - Main API client for Jiguang SMS communication
- **`SignService`** - Signature management operations (create, update, delete, sync)
- **`TemplateService`** - Template management operations (create, update, delete, sync)

### Repository Services
- **`AccountRepository`** - Account entity repository
- **`MessageRepository`** - Message entity repository
- **`TextCodeRepository`** / **`VoiceCodeRepository`** - Verification code repositories
- **`SignRepository`** - Signature entity repository
- **`TemplateRepository`** - Template entity repository
- **`AccountBalanceRepository`** - Account balance repository

### Service Features
- Automatic dependency injection via Symfony DI
- Error handling with specific exception types
- Built-in retry mechanisms for network operations
- Comprehensive logging support

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- ext-mbstring (for multi-byte string handling)

## Troubleshooting

### Common Issues

**1. "Account not found" error**
```php
// Make sure your account exists in the database
$account = $this->accountRepository->findOneBy(['appKey' => 'your_app_key']);
if (!$account) {
    throw new \Exception('Account not found');
}
```

**2. "Invalid signature" error**
```php
// Check your app_key and master_secret are correct
// Verify the account is marked as valid
$account->setValid(true);
$this->entityManager->flush();
```

**3. Database connection issues**
```bash
# Make sure you've run the migrations
php bin/console doctrine:migrations:migrate

# Check if tables exist
php bin/console doctrine:schema:validate
```

**4. Cron jobs not running**
```bash
# Check if cron jobs are registered
php bin/console cron:list

# Manually run synchronization commands
php bin/console jiguang:sms:sync-account-balance
```

### Debugging

Enable debug mode to see detailed API requests:

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

### Performance Optimization

For high-volume SMS sending, consider:

1. **Batch Processing**: Use queues for bulk SMS operations
2. **Connection Pooling**: Configure HTTP client with connection pooling
3. **Caching**: Cache frequently accessed account data
4. **Rate Limiting**: Implement rate limiting to avoid API limits

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

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
