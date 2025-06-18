<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use JiguangSmsBundle\Repository\TemplateRepository;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
#[ORM\Table(name: 'jg_sms_template')]
class Template
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '极光模板ID'])]
    private ?int $tempId = null;

    #[ORM\Column(type: Types::STRING, length: 500, options: ['comment' => '模板内容'])]
    private string $template;

    #[ORM\Column(type: Types::INTEGER, enumType: TemplateTypeEnum::class, options: ['comment' => '模板类型'])]
    private TemplateTypeEnum $type = TemplateTypeEnum::VERIFICATION;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '验证码有效期(秒)'])]
    private ?int $ttl = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, options: ['comment' => '申请说明'])]
    private ?string $remark = null;

    #[ORM\Column(type: Types::INTEGER, enumType: TemplateStatusEnum::class, options: ['comment' => '模板状态'])]
    private TemplateStatusEnum $status = TemplateStatusEnum::PENDING;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否使用中'])]
    private bool $useStatus = false;

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

    public function getTempId(): ?int
    {
        return $this->tempId;
    }

    public function setTempId(?int $tempId): self
    {
        $this->tempId = $tempId;
        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function getType(): TemplateTypeEnum
    {
        return $this->type;
    }

    public function setType(TemplateTypeEnum $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(?int $ttl): self
    {
        $this->ttl = $ttl;
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

    public function getStatus(): TemplateStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TemplateStatusEnum $status): self
    {
        $this->status = $status;
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

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;
        return $this;
    }}
