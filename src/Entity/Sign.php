<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use JiguangSmsBundle\Repository\SignRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: SignRepository::class)]
#[ORM\Table(name: 'jg_sms_sign', options: ['comment' => '极光短信签名'])]
class Sign implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属账号'])]
    private Account $account;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '极光签名ID'])]
    #[Assert\Type(type: 'integer')]
    private ?int $signId = null;

    #[ORM\Column(type: Types::STRING, length: 8, options: ['comment' => '签名内容'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    private string $sign;

    #[ORM\Column(type: Types::INTEGER, enumType: SignTypeEnum::class, options: ['comment' => '签名类型'])]
    #[Assert\Choice(callback: [SignTypeEnum::class, 'cases'])]
    private SignTypeEnum $type = SignTypeEnum::COMPANY;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, options: ['comment' => '申请说明'])]
    #[Assert\Length(max: 100)]
    private ?string $remark = null;

    #[ORM\Column(type: Types::INTEGER, enumType: SignStatusEnum::class, options: ['comment' => '签名状态'])]
    #[Assert\Choice(callback: [SignStatusEnum::class, 'cases'])]
    private SignStatusEnum $status = SignStatusEnum::PENDING;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否为默认签名'])]
    #[Assert\Type(type: 'bool')]
    private bool $isDefault = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否使用中'])]
    #[Assert\Type(type: 'bool')]
    private bool $useStatus = false;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '资质证件图片1'])]
    #[Assert\Length(max: 255)]
    private ?string $image0 = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '资质证件图片2'])]
    #[Assert\Length(max: 255)]
    private ?string $image1 = null;

    #[Assert\Type(type: 'bool')]
    private bool $syncing = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function isSyncing(): bool
    {
        return $this->syncing;
    }

    public function setSyncing(bool $syncing): void
    {
        $this->syncing = $syncing;
    }

    public function getSignId(): ?int
    {
        return $this->signId;
    }

    public function setSignId(?int $signId): void
    {
        $this->signId = $signId;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }

    public function getType(): SignTypeEnum
    {
        return $this->type;
    }

    public function setType(?SignTypeEnum $type): void
    {
        $this->type = $type ?? SignTypeEnum::COMPANY;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function getStatus(): SignStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?SignStatusEnum $status): void
    {
        $this->status = $status ?? SignStatusEnum::PENDING;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    public function isUseStatus(): bool
    {
        return $this->useStatus;
    }

    public function setUseStatus(bool $useStatus): void
    {
        $this->useStatus = $useStatus;
    }

    public function getImage0(): ?string
    {
        return $this->image0;
    }

    public function setImage0(?string $image0): void
    {
        $this->image0 = $image0;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): void
    {
        $this->image1 = $image1;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function __toString(): string
    {
        return sprintf('[%s] %s', $this->sign, $this->type->getLabel());
    }
}
