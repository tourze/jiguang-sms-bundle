<?php

namespace JiguangSmsBundle\Enum;

enum TemplateStatusEnum: int
{
    case PENDING = 0;    // 审核中
    case APPROVED = 1;   // 审核通过
    case REJECTED = 2;   // 审核不通过
}
