<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class TemplateFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const TEMPLATE_REFERENCE_PREFIX = 'jiguang-sms-template-';

    public function load(ObjectManager $manager): void
    {
        $templateData = [
            [
                'template' => '您的验证码是{{code}}，有效期为{{ttl}}分钟',
                'type' => TemplateTypeEnum::VERIFICATION,
                'status' => TemplateStatusEnum::APPROVED,
                'ttl' => 300,
            ],
            [
                'template' => '您的订单{{order_no}}已发货，请注意查收',
                'type' => TemplateTypeEnum::NOTIFICATION,
                'status' => TemplateStatusEnum::PENDING,
                'ttl' => null,
            ],
            [
                'template' => '尊敬的用户，我们有新的优惠活动{{activity}}',
                'type' => TemplateTypeEnum::MARKETING,
                'status' => TemplateStatusEnum::REJECTED,
                'ttl' => null,
            ],
        ];

        foreach ($templateData as $i => $data) {
            $accountRef = AccountFixtures::ACCOUNT_REFERENCE_PREFIX . (($i % 3) + 1);
            $account = $this->getReference($accountRef, Account::class);

            $template = new Template();
            $template->setAccount($account);
            $template->setTempId(2000 + $i);
            $template->setTemplate($data['template']);
            $template->setType($data['type']);
            $template->setStatus($data['status']);
            $template->setTtl($data['ttl']);
            $template->setRemark("测试模板{$i}的说明");
            $template->setUseStatus(TemplateStatusEnum::APPROVED === $data['status']);

            $manager->persist($template);
            $this->addReference(self::TEMPLATE_REFERENCE_PREFIX . ($i + 1), $template);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['jiguang-sms'];
    }
}
