<?php

namespace JiguangSmsBundle\Request\Template;

use JiguangSmsBundle\Request\WithAccountRequest;

abstract class AbstractTemplateRequest extends WithAccountRequest
{
    protected function getBaseUrl(): string
    {
        return 'https://api.sms.jpush.cn/v1/templates';
    }
}
