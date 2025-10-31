<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\AccountRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'jg_sms_account', options: ['comment' => '极光短信账号配置'])]
class Account implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\Column(length: 100, options: ['comment' => '标题'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 64, unique: true, options: ['comment' => 'AppKey'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $appKey = null;

    #[ORM\Column(length: 128, options: ['comment' => 'MasterSecret'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private ?string $masterSecret = null;

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAppKey(): ?string
    {
        return $this->appKey;
    }

    public function setAppKey(string $appKey): void
    {
        $this->appKey = $appKey;
    }

    public function getMasterSecret(): ?string
    {
        return $this->masterSecret;
    }

    public function setMasterSecret(string $masterSecret): void
    {
        $this->masterSecret = $masterSecret;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->title ?? '', $this->appKey ?? '');
    }
}
