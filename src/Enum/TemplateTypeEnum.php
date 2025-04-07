<?php

namespace JiguangSmsBundle\Enum;

enum TemplateTypeEnum: int
{
    case VERIFICATION = 1;   // 验证码类
    case NOTIFICATION = 2;   // 通知类
    case MARKETING = 3;      // 营销类
}
