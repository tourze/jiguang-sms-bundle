<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\MessageRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'jg_sms_message', options: ['comment' => '极光短信消息记录'])]
class Message implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属账号'])]
    private Account $account;

    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '手机号'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    #[Assert\Regex(pattern: '/^1[3-9]\d{9}$/', message: '请输入正确的手机号码')]
    private string $mobile;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true, options: ['comment' => '消息ID'])]
    #[Assert\Length(max: 32)]
    private ?string $msgId = null;

    #[ORM\ManyToOne(targetEntity: Template::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '短信模板'])]
    private Template $template;

    #[ORM\ManyToOne(targetEntity: Sign::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, options: ['comment' => '短信签名'])]
    private ?Sign $sign = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '模板参数'])]
    #[Assert\Type(type: 'array')]
    private ?array $templateParams = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true, options: ['comment' => '标签'])]
    #[Assert\Length(max: 50)]
    private ?string $tag = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '发送状态码'])]
    #[Assert\Type(type: 'integer')]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '状态接收时间'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $receiveTime = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '发送结果'])]
    #[Assert\Type(type: 'array')]
    private ?array $response = null;

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

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): void
    {
        $this->msgId = $msgId;
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): void
    {
        $this->template = $template;
    }

    public function getSign(): ?Sign
    {
        return $this->sign;
    }

    public function setSign(?Sign $sign): void
    {
        $this->sign = $sign;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getTemplateParams(): ?array
    {
        return $this->templateParams;
    }

    /**
     * @param array<string, mixed>|null $templateParams
     */
    public function setTemplateParams(?array $templateParams): void
    {
        $this->templateParams = $templateParams;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): void
    {
        $this->tag = $tag;
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

    /**
     * @return array<string, mixed>|null
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }

    /**
     * @param array<string, mixed>|null $response
     */
    public function setResponse(?array $response): void
    {
        $this->response = $response;
    }

    public function __toString(): string
    {
        return sprintf('[%s] %s - %s', $this->mobile, $this->template->getId(), $this->msgId ?? 'N/A');
    }
}
