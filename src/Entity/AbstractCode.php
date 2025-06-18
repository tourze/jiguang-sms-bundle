<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\MappedSuperclass]
abstract class AbstractCode
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false)]
    protected Account $account;

    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '手机号'])]
    protected string $mobile;

    #[ORM\Column(type: Types::STRING, length: 6, options: ['comment' => '验证码'])]
    protected string $code;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '有效期(秒)'])]
    protected int $ttl = 60;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true, options: ['comment' => '消息ID'])]
    protected ?string $msgId = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否已验证'])]
    protected bool $verified = false;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '发送状态码'])]
    protected ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '状态接收时间'])]
    protected ?\DateTimeImmutable $receiveTime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '验证时间'])]
    protected ?\DateTimeImmutable $verifyTime = null;

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

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function setTtl(int $ttl): self
    {
        $this->ttl = $ttl;
        return $this;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): self
    {
        $this->msgId = $msgId;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;
        if ($verified) {
            $this->verifyTime = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getVerifyTime(): ?\DateTimeImmutable
    {
        return $this->verifyTime;
    }

    public function setVerifyTime(?\DateTimeImmutable $verifyTime): self
    {
        $this->verifyTime = $verifyTime;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getReceiveTime(): ?\DateTimeImmutable
    {
        return $this->receiveTime;
    }

    public function setReceiveTime(?\DateTimeImmutable $receiveTime): self
    {
        $this->receiveTime = $receiveTime;
        return $this;
    }

    public function isDelivered(): bool
    {
        return $this->status === 4001;
    }}
