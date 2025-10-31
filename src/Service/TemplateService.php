<?php

namespace JiguangSmsBundle\Service;

use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Exception\InvalidTemplateStatusException;
use JiguangSmsBundle\Request\Template\CreateTemplateRequest;
use JiguangSmsBundle\Request\Template\DeleteTemplateRequest;
use JiguangSmsBundle\Request\Template\GetTemplateRequest;
use JiguangSmsBundle\Request\Template\UpdateTemplateRequest;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class TemplateService
{
    public function __construct(
        private JiguangSmsService $jiguangSmsService,
    ) {
    }

    /**
     * 创建远程模板
     *
     * @throws TransportExceptionInterface
     */
    public function createRemoteTemplate(Template $template): void
    {
        $request = new CreateTemplateRequest();
        $request->setAccount($template->getAccount());
        $request->setTemplate($template);

        $data = $this->jiguangSmsService->request($request);
        $tempId = is_array($data) && isset($data['temp_id']) && is_int($data['temp_id']) ? $data['temp_id'] : null;
        $template->setTempId($tempId);
    }

    /**
     * 更新远程模板
     *
     * @throws TransportExceptionInterface
     */
    public function updateRemoteTemplate(Template $template): void
    {
        $request = new UpdateTemplateRequest();
        $request->setAccount($template->getAccount());
        $request->setTemplate($template);

        $this->jiguangSmsService->request($request);
    }

    /**
     * 删除远程模板
     *
     * @throws TransportExceptionInterface
     */
    public function deleteRemoteTemplate(Template $template): void
    {
        $request = new DeleteTemplateRequest();
        $request->setAccount($template->getAccount());
        $request->setTemplate($template);

        $this->jiguangSmsService->request($request);
    }

    /**
     * 同步模板状态
     *
     * @throws TransportExceptionInterface
     */
    public function syncTemplateStatus(Template $template): void
    {
        $request = new GetTemplateRequest();
        $request->setAccount($template->getAccount());
        $request->setTemplate($template);

        $data = $this->jiguangSmsService->request($request);

        $statusValue = is_array($data) && isset($data['status']) ? $data['status'] : null;
        if (null === $statusValue) {
            throw new InvalidTemplateStatusException('Status not found in response');
        }

        $status = match ($statusValue) {
            0 => TemplateStatusEnum::PENDING,
            1 => TemplateStatusEnum::APPROVED,
            2 => TemplateStatusEnum::REJECTED,
            default => throw new InvalidTemplateStatusException(is_scalar($statusValue) ? (string) $statusValue : 'Invalid status type'),
        };
        $template->setStatus($status);
    }
}
