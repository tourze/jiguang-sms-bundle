<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Repository\SignRepository;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: SignRepository::class)]
#[ORM\Table(name: 'jg_sms_sign')]
#[ORM\HasLifecycleCallbacks]
class Sign
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '极光签名ID'])]
    private ?int $signId = null;

    #[ORM\Column(type: Types::STRING, length: 8, options: ['comment' => '签名内容'])]
    private string $sign;

    #[ORM\Column(type: Types::INTEGER, enumType: SignTypeEnum::class, options: ['comment' => '签名类型'])]
    private SignTypeEnum $type = SignTypeEnum::COMPANY;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, options: ['comment' => '申请说明'])]
    private ?string $remark = null;

    #[ORM\Column(type: Types::INTEGER, enumType: SignStatusEnum::class, options: ['comment' => '签名状态'])]
    private SignStatusEnum $status = SignStatusEnum::PENDING;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否为默认签名'])]
    private bool $isDefault = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否使用中'])]
    private bool $useStatus = false;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '资质证件图片1'])]
    private ?string $image0 = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '资质证件图片2'])]
    private ?string $image1 = null;

    private bool $syncing = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isSyncing(): bool
    {
        return $this->syncing;
    }

    public function setSyncing(bool $syncing): static
    {
        $this->syncing = $syncing;
        return $this;
    }

    public function getSignId(): ?int
    {
        return $this->signId;
    }

    public function setSignId(?int $signId): self
    {
        $this->signId = $signId;
        return $this;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;
        return $this;
    }

    public function getType(): SignTypeEnum
    {
        return $this->type;
    }

    public function setType(SignTypeEnum $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;
        return $this;
    }

    public function getStatus(): SignStatusEnum
    {
        return $this->status;
    }

    public function setStatus(SignStatusEnum $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function isUseStatus(): bool
    {
        return $this->useStatus;
    }

    public function setUseStatus(bool $useStatus): self
    {
        $this->useStatus = $useStatus;
        return $this;
    }

    public function getImage0(): ?string
    {
        return $this->image0;
    }

    public function setImage0(?string $image0): self
    {
        $this->image0 = $image0;
        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): self
    {
        $this->image1 = $image1;
        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;
        return $this;
    }}
