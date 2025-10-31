<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Repository\TemplateRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
#[ORM\Table(name: 'jg_sms_template', options: ['comment' => '极光短信模板'])]
class Template implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属账号'])]
    private Account $account;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '极光模板ID'])]
    #[Assert\Type(type: 'integer')]
    private ?int $tempId = null;

    #[ORM\Column(type: Types::STRING, length: 500, options: ['comment' => '模板内容'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private string $template;

    #[ORM\Column(type: Types::INTEGER, enumType: TemplateTypeEnum::class, options: ['comment' => '模板类型'])]
    #[Assert\Choice(callback: [TemplateTypeEnum::class, 'cases'])]
    private TemplateTypeEnum $type = TemplateTypeEnum::VERIFICATION;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '验证码有效期(秒)'])]
    #[Assert\PositiveOrZero]
    private ?int $ttl = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, options: ['comment' => '申请说明'])]
    #[Assert\Length(max: 100)]
    private ?string $remark = null;

    #[ORM\Column(type: Types::INTEGER, enumType: TemplateStatusEnum::class, options: ['comment' => '模板状态'])]
    #[Assert\Choice(callback: [TemplateStatusEnum::class, 'cases'])]
    private TemplateStatusEnum $status = TemplateStatusEnum::PENDING;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否使用中'])]
    #[Assert\Type(type: 'bool')]
    private bool $useStatus = false;

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

    public function getTempId(): ?int
    {
        return $this->tempId;
    }

    public function setTempId(?int $tempId): void
    {
        $this->tempId = $tempId;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getType(): TemplateTypeEnum
    {
        return $this->type;
    }

    public function setType(?TemplateTypeEnum $type): void
    {
        $this->type = $type ?? TemplateTypeEnum::VERIFICATION;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(?int $ttl): void
    {
        $this->ttl = $ttl;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function getStatus(): TemplateStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?TemplateStatusEnum $status): void
    {
        $this->status = $status ?? TemplateStatusEnum::PENDING;
    }

    public function isUseStatus(): bool
    {
        return $this->useStatus;
    }

    public function setUseStatus(bool $useStatus): void
    {
        $this->useStatus = $useStatus;
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
        $preview = mb_strlen($this->template) > 50 ? mb_substr($this->template, 0, 50) . '...' : $this->template;

        return sprintf('[%s] %s (%s)', $this->tempId ?? 'N/A', $preview, $this->status->value);
    }
}
