<?php

namespace JiguangSmsBundle\Enum;

enum SignStatusEnum: int
{
    case PENDING = 0;    // 审核中
    case APPROVED = 1;   // 审核通过
    case REJECTED = 2;   // 审核不通过
    case DELETED = 3;    // 已删除
}
