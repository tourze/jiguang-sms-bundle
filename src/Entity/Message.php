<?php

namespace JiguangSmsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JiguangSmsBundle\Repository\MessageRepository;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'jg_sms_message', options: ['comment' => '极光短信消息记录'])]
class Message implements \Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '所属账号'])]
    private Account $account;

    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '手机号'])]
    private string $mobile;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true, options: ['comment' => '消息ID'])]
    private ?string $msgId = null;

    #[ORM\ManyToOne(targetEntity: Template::class)]
    #[ORM\JoinColumn(nullable: false, options: ['comment' => '短信模板'])]
    private Template $template;

    #[ORM\ManyToOne(targetEntity: Sign::class)]
    #[ORM\JoinColumn(nullable: true, options: ['comment' => '短信签名'])]
    private ?Sign $sign = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '模板参数'])]
    private ?array $templateParams = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true, options: ['comment' => '标签'])]
    private ?string $tag = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '发送状态码'])]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '状态接收时间'])]
    private ?\DateTimeImmutable $receiveTime = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '发送结果'])]
    private ?array $response = null;

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

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): self
    {
        $this->msgId = $msgId;
        return $this;
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function getSign(): ?Sign
    {
        return $this->sign;
    }

    public function setSign(?Sign $sign): self
    {
        $this->sign = $sign;
        return $this;
    }

    public function getTemplateParams(): ?array
    {
        return $this->templateParams;
    }

    public function setTemplateParams(?array $templateParams): self
    {
        $this->templateParams = $templateParams;
        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
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
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function setResponse(?array $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf('[%s] %s - %s', $this->mobile, $this->template->getId(), $this->msgId ?? 'N/A');
    }
}
