<?php

namespace JiguangSmsBundle\Request\Sign;

use JiguangSmsBundle\Request\WithAccountRequest;

abstract class AbstractSignRequest extends WithAccountRequest
{
    protected function getBaseUrl(): string
    {
        return 'https://api.sms.jpush.cn/v1/sign';
    }
}
