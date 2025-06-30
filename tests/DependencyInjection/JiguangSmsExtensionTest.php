<?php

namespace JiguangSmsBundle\Tests\DependencyInjection;

use JiguangSmsBundle\DependencyInjection\JiguangSmsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JiguangSmsExtensionTest extends TestCase
{
    private JiguangSmsExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new JiguangSmsExtension();
        $this->container = new ContainerBuilder();
    }

    public function test_load_registersExpectedServices(): void
    {
        $this->extension->load([], $this->container);

        // 验证核心服务已注册
        $this->assertTrue($this->container->hasDefinition('JiguangSmsBundle\Service\JiguangSmsService'));
        $this->assertTrue($this->container->hasDefinition('JiguangSmsBundle\Service\SignService'));
        $this->assertTrue($this->container->hasDefinition('JiguangSmsBundle\Service\TemplateService'));
    }

    public function test_load_withEmptyConfig_doesNotThrowException(): void
    {
        $this->expectNotToPerformAssertions();
        
        $this->extension->load([], $this->container);
    }

    public function test_getAlias_returnsExpectedAlias(): void
    {
        $alias = $this->extension->getAlias();
        
        $this->assertEquals('jiguang_sms', $alias);
    }
} 