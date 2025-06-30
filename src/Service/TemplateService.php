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

class TemplateService
{
    public function __construct(
        private readonly JiguangSmsService $jiguangSmsService,
    ) {
    }

    /**
     * 创建远程模板
     * @throws TransportExceptionInterface
     */
    public function createRemoteTemplate(Template $template): void
    {
        $request = new CreateTemplateRequest();
        $request->setAccount($template->getAccount());
        $request->setTemplate($template);

        $data = $this->jiguangSmsService->request($request);
        $template->setTempId($data['temp_id']);
    }

    /**
     * 更新远程模板
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
     * @throws TransportExceptionInterface
     */
    public function syncTemplateStatus(Template $template): void
    {
        $request = new GetTemplateRequest();
        $request->setAccount($template->getAccount());
        $request->setTemplate($template);

        $data = $this->jiguangSmsService->request($request);

        $status = match ($data['status']) {
            0 => TemplateStatusEnum::PENDING,
            1 => TemplateStatusEnum::APPROVED,
            2 => TemplateStatusEnum::REJECTED,
            default => throw new InvalidTemplateStatusException((string)$data['status']),
        };
        $template->setStatus($status);
    }
}
