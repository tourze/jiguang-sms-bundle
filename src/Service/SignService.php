<?php

namespace JiguangSmsBundle\Service;

use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Request\Sign\CreateSignRequest;
use JiguangSmsBundle\Request\Sign\DeleteSignRequest;
use JiguangSmsBundle\Request\Sign\GetSignRequest;
use JiguangSmsBundle\Request\Sign\UpdateSignRequest;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SignService
{
    public function __construct(
        private readonly JiguangSmsService $jiguangSmsService,
    ) {
    }

    /**
     * 创建远程签名
     * @throws TransportExceptionInterface
     */
    public function createRemoteSign(Sign $sign): void
    {
        $request = new CreateSignRequest();
        $request->setAccount($sign->getAccount());
        $request->setSign($sign);

        $data = $this->jiguangSmsService->request($request);
        $sign->setSignId($data['sign_id']);
    }

    /**
     * 更新远程签名
     * @throws TransportExceptionInterface
     */
    public function updateRemoteSign(Sign $sign): void
    {
        $request = new UpdateSignRequest();
        $request->setAccount($sign->getAccount());
        $request->setSign($sign);

        $this->jiguangSmsService->request($request);
    }

    /**
     * 删除远程签名
     * @throws TransportExceptionInterface
     */
    public function deleteRemoteSign(Sign $sign): void
    {
        $request = new DeleteSignRequest();
        $request->setAccount($sign->getAccount());
        $request->setSign($sign);

        $this->jiguangSmsService->request($request);
    }

    /**
     * 同步签名状态
     * @throws TransportExceptionInterface
     */
    public function syncSignStatus(Sign $sign): void
    {
        $request = new GetSignRequest();
        $request->setAccount($sign->getAccount());
        $request->setSign($sign);

        $data = $this->jiguangSmsService->request($request);

        $status = match ($data['status']) {
            0 => SignStatusEnum::PENDING,
            1 => SignStatusEnum::APPROVED,
            2 => SignStatusEnum::REJECTED,
            3 => SignStatusEnum::DELETED,
            default => throw new \RuntimeException('Unknown sign status'),
        };
        $sign->setStatus($status);

        $sign->setIsDefault(!!$data['is_default']);
        $sign->setUseStatus(!!$data['use_status']);
    }
}
