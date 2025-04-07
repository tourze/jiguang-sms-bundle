<?php

namespace JiguangSmsBundle\Enum;

enum SignTypeEnum: int
{
    case COMPANY = 1;           // 公司名称全称或简称
    case ICP_WEBSITE = 2;       // 工信部备案的网站全称或简称
    case APP = 3;               // APP应用名称或简称
    case WECHAT = 4;           // 公众号小程序全称或简称
    case TRADEMARK = 5;         // 商标名称全称或简称
    case OTHER = 6;             // 其他

    public function getDescription(): string
    {
        return match ($this) {
            self::COMPANY => '公司名称全称或简称：需提供签名所属主体的营业执照复印件图片及对应法人代表的身份证正反面复印件图片，均需加盖公章',
            self::ICP_WEBSITE => '工信部备案的网站全称或简称：需提供签名所属的已备案的icp备案截图复印件图片、主办单位的营业执照复印件图片及对应法人代表的身份证正反面复印件图片，均需加盖公章',
            self::APP => 'APP应用名称或简称：需提供签名所属的任意应用商店的下载链接、APP软著证明复印件图片及开发者的营业执照复印件图片、对应法人代表的身份证正反面复印件图片，均需加盖公章',
            self::WECHAT => '公众号小程序全称或简称：需提供签名所属的公众号小程序含主体的页面截图、开发者主体营业执照复印件图片、对应法人代表的身份证正反面复印件图片，均需加盖公章',
            self::TRADEMARK => '商标名称全称或简称：需提供签名所属商标注册证书复印件图片及商标主体营业执照复印件图片、对应法人代表身份证正反面复印件图片，均需加盖公章',
            self::OTHER => '其他：申请的签名与所属主体不一致或涉及第三方权益时，需提供第三方授权委托书、第三方签名相关资质',
        };
    }
}
