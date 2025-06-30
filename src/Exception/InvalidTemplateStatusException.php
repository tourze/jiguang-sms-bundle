<?php

namespace JiguangSmsBundle\Exception;

/**
 * 无效模板状态异常
 */
class InvalidTemplateStatusException extends JiguangSmsException
{
    public function __construct(string $status = '')
    {
        parent::__construct('无效的模板状态: ' . $status);
    }
} 