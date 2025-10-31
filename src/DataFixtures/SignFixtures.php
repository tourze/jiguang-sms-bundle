<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class SignFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const SIGN_REFERENCE_PREFIX = 'jiguang-sms-sign-';

    public function load(ObjectManager $manager): void
    {
        $signData = [
            ['sign' => '测试公司', 'type' => SignTypeEnum::COMPANY, 'status' => SignStatusEnum::APPROVED],
            ['sign' => '验证码', 'type' => SignTypeEnum::APP, 'status' => SignStatusEnum::PENDING],
            ['sign' => '通知', 'type' => SignTypeEnum::ICP_WEBSITE, 'status' => SignStatusEnum::REJECTED],
        ];

        foreach ($signData as $i => $data) {
            $accountRef = AccountFixtures::ACCOUNT_REFERENCE_PREFIX . (($i % 3) + 1);
            $account = $this->getReference($accountRef, Account::class);

            $sign = new Sign();
            $sign->setAccount($account);
            $sign->setSignId(1000 + $i);
            $sign->setSign($data['sign']);
            $sign->setType($data['type']);
            $sign->setStatus($data['status']);
            $sign->setRemark("测试签名{$i}的说明");
            $sign->setIsDefault(0 === $i);
            $sign->setUseStatus(SignStatusEnum::APPROVED === $data['status']);

            $manager->persist($sign);
            $this->addReference(self::SIGN_REFERENCE_PREFIX . ($i + 1), $sign);
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
