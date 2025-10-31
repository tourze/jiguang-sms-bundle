<?php

namespace JiguangSmsBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class JiguangSmsExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}
