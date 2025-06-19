<?php

namespace JiguangSmsBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CodeTypeEnum: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case TEXT = 1;
    case VOICE = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::TEXT => '文本验证码',
            self::VOICE => '语音验证码',
        };
    }
}
