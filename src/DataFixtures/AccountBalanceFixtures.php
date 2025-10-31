<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\AccountBalance;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class AccountBalanceFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const BALANCE_REFERENCE_PREFIX = 'jiguang-sms-balance-';

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 3; ++$i) {
            $account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE_PREFIX . $i, Account::class);

            $balance = new AccountBalance();
            $balance->setAccount($account);
            $balance->setBalance(mt_rand(100, 10000));
            $balance->setVoice(mt_rand(50, 1000));
            $balance->setIndustry(mt_rand(200, 5000));
            $balance->setMarket(mt_rand(100, 2000));

            $manager->persist($balance);
            $this->addReference(self::BALANCE_REFERENCE_PREFIX . $i, $balance);
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
