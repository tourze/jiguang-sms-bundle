<?php

namespace JiguangSmsBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum SignStatusEnum: int implements Itemable, Labelable, Selectable, BadgeInterface
{
    use ItemTrait;
    use SelectTrait;

    case PENDING = 0;    // 审核中
    case APPROVED = 1;   // 审核通过
    case REJECTED = 2;   // 审核不通过
    case DELETED = 3;    // 已删除

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => '审核中',
            self::APPROVED => '审核通过',
            self::REJECTED => '审核不通过',
            self::DELETED => '已删除',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::PENDING => self::WARNING,
            self::APPROVED => self::SUCCESS,
            self::REJECTED => self::DANGER,
            self::DELETED => self::SECONDARY,
        };
    }
}
