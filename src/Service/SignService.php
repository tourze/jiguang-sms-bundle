<?php

namespace JiguangSmsBundle\Service;

use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Exception\InvalidSignStatusException;
use JiguangSmsBundle\Request\Sign\CreateSignRequest;
use JiguangSmsBundle\Request\Sign\DeleteSignRequest;
use JiguangSmsBundle\Request\Sign\GetSignRequest;
use JiguangSmsBundle\Request\Sign\UpdateSignRequest;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class SignService
{
    public function __construct(
        private JiguangSmsService $jiguangSmsService,
    ) {
    }

    /**
     * 创建远程签名
     *
     * @throws TransportExceptionInterface
     */
    public function createRemoteSign(Sign $sign): void
    {
        $request = new CreateSignRequest();
        $request->setAccount($sign->getAccount());
        $request->setSign($sign);

        $data = $this->jiguangSmsService->request($request);
        $signId = is_array($data) && isset($data['sign_id']) && is_int($data['sign_id']) ? $data['sign_id'] : null;
        $sign->setSignId($signId);
    }

    /**
     * 更新远程签名
     *
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
     *
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
     *
     * @throws TransportExceptionInterface
     */
    public function syncSignStatus(Sign $sign): void
    {
        $request = new GetSignRequest();
        $request->setAccount($sign->getAccount());
        $request->setSign($sign);

        $data = $this->jiguangSmsService->request($request);

        $statusValue = is_array($data) && isset($data['status']) ? $data['status'] : null;
        if (null === $statusValue) {
            throw new InvalidSignStatusException('Status not found in response');
        }

        $status = match ($statusValue) {
            0 => SignStatusEnum::PENDING,
            1 => SignStatusEnum::APPROVED,
            2 => SignStatusEnum::REJECTED,
            3 => SignStatusEnum::DELETED,
            default => throw new InvalidSignStatusException(is_scalar($statusValue) ? (string) $statusValue : 'Invalid status type'),
        };
        $sign->setStatus($status);

        $sign->setIsDefault((bool) (is_array($data) && isset($data['is_default']) ? $data['is_default'] : false));
        $sign->setUseStatus((bool) (is_array($data) && isset($data['use_status']) ? $data['use_status'] : false));
    }
}
