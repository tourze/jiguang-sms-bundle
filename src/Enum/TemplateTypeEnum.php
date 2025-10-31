<?php

namespace JiguangSmsBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum TemplateTypeEnum: int implements Itemable, Labelable, Selectable, BadgeInterface
{
    use ItemTrait;
    use SelectTrait;

    case VERIFICATION = 1;   // 验证码类
    case NOTIFICATION = 2;   // 通知类
    case MARKETING = 3;      // 营销类

    public function getLabel(): string
    {
        return match ($this) {
            self::VERIFICATION => '验证码类',
            self::NOTIFICATION => '通知类',
            self::MARKETING => '营销类',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::VERIFICATION => self::PRIMARY,
            self::NOTIFICATION => self::INFO,
            self::MARKETING => self::SUCCESS,
        };
    }
}
