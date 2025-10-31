<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Service;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\AccountBalance;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Entity\TextCode;
use JiguangSmsBundle\Entity\VoiceCode;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('短信服务')) {
            $item->addChild('短信服务');
        }

        $smsMenu = $item->getChild('短信服务');
        if (null === $smsMenu) {
            return;
        }

        // 配置管理
        $smsMenu
            ->addChild('账号管理')
            ->setUri($this->linkGenerator->getCurdListPage(Account::class))
            ->setAttribute('icon', 'fas fa-user-cog')
        ;

        $smsMenu
            ->addChild('余量管理')
            ->setUri($this->linkGenerator->getCurdListPage(AccountBalance::class))
            ->setAttribute('icon', 'fas fa-coins')
        ;

        // 模板签名管理
        $smsMenu
            ->addChild('签名管理')
            ->setUri($this->linkGenerator->getCurdListPage(Sign::class))
            ->setAttribute('icon', 'fas fa-signature')
        ;

        $smsMenu
            ->addChild('模板管理')
            ->setUri($this->linkGenerator->getCurdListPage(Template::class))
            ->setAttribute('icon', 'fas fa-file-alt')
        ;

        // 发送记录
        $smsMenu
            ->addChild('短信记录')
            ->setUri($this->linkGenerator->getCurdListPage(Message::class))
            ->setAttribute('icon', 'fas fa-sms')
        ;

        $smsMenu
            ->addChild('短信验证码')
            ->setUri($this->linkGenerator->getCurdListPage(TextCode::class))
            ->setAttribute('icon', 'fas fa-shield-alt')
        ;

        $smsMenu
            ->addChild('语音验证码')
            ->setUri($this->linkGenerator->getCurdListPage(VoiceCode::class))
            ->setAttribute('icon', 'fas fa-phone-volume')
        ;
    }
}
