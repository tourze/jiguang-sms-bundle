<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Entity\TextCode;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class TextCodeFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const TEXT_CODE_REFERENCE_PREFIX = 'jiguang-sms-text-code-';

    public function load(ObjectManager $manager): void
    {
        $phoneNumbers = [
            '13900139001',
            '13900139002',
            '13900139003',
        ];

        for ($i = 1; $i <= 5; ++$i) {
            $accountRef = AccountFixtures::ACCOUNT_REFERENCE_PREFIX . (($i % 3) + 1);
            $account = $this->getReference($accountRef, Account::class);

            $templateRef = TemplateFixtures::TEMPLATE_REFERENCE_PREFIX . 1; // 使用验证码模板
            $template = $this->getReference($templateRef, Template::class);

            $signRef = SignFixtures::SIGN_REFERENCE_PREFIX . 1; // 使用已审核的签名
            $sign = $this->getReference($signRef, Sign::class);

            $textCode = new TextCode();
            $textCode->setAccount($account);
            $textCode->setMobile($phoneNumbers[$i % count($phoneNumbers)]);
            $textCode->setCode(str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
            $textCode->setTtl(300);
            $textCode->setMsgId('txt_msg_' . str_pad((string) $i, 6, '0', STR_PAD_LEFT));
            $textCode->setTemplate($template);
            $textCode->setSign($sign);
            $textCode->setVerified(1 === $i % 3);
            $textCode->setStatus(4001);
            $textCode->setReceiveTime(new \DateTimeImmutable('-' . mt_rand(5, 60) . ' minutes'));

            $manager->persist($textCode);
            $this->addReference(self::TEXT_CODE_REFERENCE_PREFIX . $i, $textCode);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
            SignFixtures::class,
            TemplateFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['jiguang-sms'];
    }
}
