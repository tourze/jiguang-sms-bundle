<?php

declare(strict_types=1);

namespace JiguangSmsBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Message;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Entity\Template;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class MessageFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const MESSAGE_REFERENCE_PREFIX = 'jiguang-sms-message-';

    public function load(ObjectManager $manager): void
    {
        $phoneNumbers = [
            '13800138001',
            '13800138002',
            '13800138003',
            '13800138004',
            '13800138005',
        ];

        $statusCodes = [4001, 4002, 4003, 4004, 4005];

        for ($i = 1; $i <= 10; ++$i) {
            $accountRef = AccountFixtures::ACCOUNT_REFERENCE_PREFIX . (($i % 3) + 1);
            $account = $this->getReference($accountRef, Account::class);

            $templateRef = TemplateFixtures::TEMPLATE_REFERENCE_PREFIX . (($i % 3) + 1);
            $template = $this->getReference($templateRef, Template::class);

            $signRef = SignFixtures::SIGN_REFERENCE_PREFIX . (($i % 3) + 1);
            $sign = $this->getReference($signRef, Sign::class);

            $message = new Message();
            $message->setAccount($account);
            $message->setMobile($phoneNumbers[$i % count($phoneNumbers)]);
            $message->setMsgId('msg_' . str_pad((string) $i, 8, '0', STR_PAD_LEFT));
            $message->setTemplate($template);
            $message->setSign($sign);
            $message->setTemplateParams(['code' => '123456', 'ttl' => '5']);
            $message->setTag("test_tag_{$i}");
            $message->setStatus($statusCodes[$i % count($statusCodes)]);
            $message->setReceiveTime(new \DateTimeImmutable('-' . mt_rand(1, 30) . ' minutes'));
            $message->setResponse(['result' => 'success', 'message' => 'sent']);

            $manager->persist($message);
            $this->addReference(self::MESSAGE_REFERENCE_PREFIX . $i, $message);
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
