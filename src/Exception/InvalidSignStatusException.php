<?php

namespace JiguangSmsBundle\Exception;

/**
 * 无效签名状态异常
 */
class InvalidSignStatusException extends JiguangSmsException
{
    public function __construct(string $status = '')
    {
        parent::__construct('无效的签名状态: ' . $status);
    }
}
