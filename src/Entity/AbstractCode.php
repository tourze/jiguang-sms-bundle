<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\MappedSuperclass]
abstract class AbstractCode implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    protected int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属账号'])]
    protected Account $account;

    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '手机号'])]
    #[Assert\NotBlank(message: '手机号不能为空')]
    #[Assert\Length(max: 20)]
    #[Assert\Regex(pattern: '/^1[3-9]\d{9}$/', message: '请输入正确的手机号码')]
    protected string $mobile;

    #[ORM\Column(type: Types::STRING, length: 6, options: ['comment' => '验证码'])]
    #[Assert\NotBlank(message: '验证码不能为空')]
    #[Assert\Length(min: 4, max: 6)]
    #[Assert\Regex(pattern: '/^\d+$/', message: '验证码只能包含数字')]
    protected string $code;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '有效期(秒)'])]
    #[Assert\PositiveOrZero(message: '有效期必须大于或等于0')]
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): void
    {
        $this->msgId = $msgId;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
        if ($verified) {
            $this->verifyTime = new \DateTimeImmutable();
        }
    }

    public function getVerifyTime(): ?\DateTimeImmutable
    {
        return $this->verifyTime;
    }

    public function setVerifyTime(?\DateTimeImmutable $verifyTime): void
    {
        $this->verifyTime = $verifyTime;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getReceiveTime(): ?\DateTimeImmutable
    {
        return $this->receiveTime;
    }

    public function setReceiveTime(?\DateTimeImmutable $receiveTime): void
    {
        $this->receiveTime = $receiveTime;
    }

    public function isDelivered(): bool
    {
        return 4001 === $this->status;
    }

    public function __toString(): string
    {
        return sprintf('[%s] %s - %s', $this->mobile, $this->code, $this->msgId ?? 'N/A');
    }
}
