<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\AccountBalanceRepository;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: AccountBalanceRepository::class)]
#[ORM\Table(name: 'jg_sms_account_balance', options: ['comment' => '极光短信账号余量'])]
class AccountBalance implements \Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\OneToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '账号ID'])]
    private Account $account;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '全类型短信余量'])]
    private ?int $balance = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '语音短信余量'])]
    private ?int $voice = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '行业短信余量'])]
    private ?int $industry = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '营销短信余量'])]
    private ?int $market = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(?int $balance): self
    {
        $this->balance = $balance;
        return $this;
    }

    public function getVoice(): ?int
    {
        return $this->voice;
    }

    public function setVoice(?int $voice): self
    {
        $this->voice = $voice;
        return $this;
    }

    public function getIndustry(): ?int
    {
        return $this->industry;
    }

    public function setIndustry(?int $industry): self
    {
        $this->industry = $industry;
        return $this;
    }

    public function getMarket(): ?int
    {
        return $this->market;
    }

    public function setMarket(?int $market): self
    {
        $this->market = $market;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf('账号%s 余量: 全类型=%d, 语音=%d, 行业=%d, 营销=%d', 
            $this->account->getTitle() ?? 'Unknown',
            $this->balance ?? 0,
            $this->voice ?? 0,
            $this->industry ?? 0,
            $this->market ?? 0
        );
    }}
