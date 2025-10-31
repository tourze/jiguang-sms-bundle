<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\VoiceCode;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class VoiceCodeFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const VOICE_CODE_REFERENCE_PREFIX = 'jiguang-sms-voice-code-';

    public function load(ObjectManager $manager): void
    {
        $phoneNumbers = [
            '13700137001',
            '13700137002',
            '13700137003',
        ];

        for ($i = 1; $i <= 3; ++$i) {
            $accountRef = AccountFixtures::ACCOUNT_REFERENCE_PREFIX . $i;
            $account = $this->getReference($accountRef, Account::class);

            $voiceCode = new VoiceCode();
            $voiceCode->setAccount($account);
            $voiceCode->setMobile($phoneNumbers[$i - 1]);
            $voiceCode->setCode(str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT));
            $voiceCode->setTtl(60);
            $voiceCode->setMsgId('voice_msg_' . str_pad((string) $i, 6, '0', STR_PAD_LEFT));
            $voiceCode->setVerified(0 === $i % 2);
            $voiceCode->setStatus(4001);
            $voiceCode->setReceiveTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' minutes'));

            $manager->persist($voiceCode);
            $this->addReference(self::VOICE_CODE_REFERENCE_PREFIX . $i, $voiceCode);
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
