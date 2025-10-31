<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class AccountFixtures extends Fixture implements FixtureGroupInterface
{
    public const ACCOUNT_REFERENCE_PREFIX = 'jiguang-sms-account-';

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 3; ++$i) {
            $account = new Account();
            $account->setTitle("测试账号{$i}");
            $account->setAppKey("test_app_key_{$i}");
            $account->setMasterSecret("test_master_secret_{$i}");
            $account->setValid(1 === $i % 2);

            $manager->persist($account);
            $this->addReference(self::ACCOUNT_REFERENCE_PREFIX . $i, $account);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['jiguang-sms'];
    }
}
