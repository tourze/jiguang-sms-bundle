<?php

namespace JiguangSmsBundle\Tests\Entity;

use JiguangSmsBundle\Entity\Account;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Sign::class)]
final class SignTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new Sign();
    }

    /** @return iterable<array{string, mixed}> */
    public static function propertiesProvider(): iterable
    {
        yield 'signId' => ['signId', 123];
        yield 'sign' => ['sign', '测试签名'];
        yield 'type' => ['type', SignTypeEnum::COMPANY];
        yield 'remark' => ['remark', '测试备注'];
        yield 'useStatus' => ['useStatus', true];
        yield 'status' => ['status', SignStatusEnum::APPROVED];
        yield 'image0' => ['image0', 'image0.jpg'];
        yield 'image1' => ['image1', 'image1.jpg'];
        yield 'syncing' => ['syncing', true];
    }

    public function testGetterAndSetterMethods(): void
    {
        $sign = new Sign();

        // 测试签名ID
        $sign->setSignId(123);
        $this->assertEquals(123, $sign->getSignId());

        // 测试签名
        $sign->setSign('测试签名');
        $this->assertEquals('测试签名', $sign->getSign());

        // 测试类型
        $sign->setType(SignTypeEnum::COMPANY);
        $this->assertEquals(SignTypeEnum::COMPANY, $sign->getType());

        // 测试备注
        $sign->setRemark('测试备注');
        $this->assertEquals('测试备注', $sign->getRemark());

        // 测试是否默认
        $sign->setIsDefault(true);
        $this->assertTrue($sign->isDefault());

        // 测试使用状态
        $sign->setUseStatus(true);
        $this->assertTrue($sign->isUseStatus());

        // 测试审核状态
        $sign->setStatus(SignStatusEnum::APPROVED);
        $this->assertEquals(SignStatusEnum::APPROVED, $sign->getStatus());

        // 测试图片
        $sign->setImage0('image0.jpg');
        $this->assertEquals('image0.jpg', $sign->getImage0());

        $sign->setImage1('image1.jpg');
        $this->assertEquals('image1.jpg', $sign->getImage1());

        // 测试创建时间和更新时间
        $now = new \DateTimeImmutable();
        $sign->setCreateTime($now);
        $this->assertSame($now, $sign->getCreateTime());

        $updateTime = new \DateTimeImmutable('+1 hour');
        $sign->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $sign->getUpdateTime());
    }

    public function testDefaultValues(): void
    {
        $sign = new Sign();

        $this->assertEquals(0, $sign->getId());
        $this->assertNull($sign->getSignId());

        // sign是非空字段，不能测试默认值为null
        // $this->assertNull($sign->getSign());

        $this->assertEquals(SignTypeEnum::COMPANY, $sign->getType());
        $this->assertNull($sign->getRemark());
        $this->assertEquals(SignStatusEnum::PENDING, $sign->getStatus());
        $this->assertFalse($sign->isDefault());
        $this->assertFalse($sign->isUseStatus());
        $this->assertNull($sign->getImage0());
        $this->assertNull($sign->getImage1());
        $this->assertNull($sign->getCreateTime());
        $this->assertNull($sign->getUpdateTime());
    }

    public function testAccountRelation(): void
    {
        $sign = new Sign();
        $account = new Account();
        $account->setTitle('测试账号');
        $account->setAppKey('test_app_key');

        $sign->setAccount($account);

        $this->assertSame($account, $sign->getAccount());
    }

    public function testStatusEnumValidation(): void
    {
        $sign = new Sign();

        $sign->setStatus(SignStatusEnum::PENDING);
        $this->assertEquals(SignStatusEnum::PENDING, $sign->getStatus());

        $sign->setStatus(SignStatusEnum::APPROVED);
        $this->assertEquals(SignStatusEnum::APPROVED, $sign->getStatus());

        $sign->setStatus(SignStatusEnum::REJECTED);
        $this->assertEquals(SignStatusEnum::REJECTED, $sign->getStatus());

        $sign->setStatus(SignStatusEnum::DELETED);
        $this->assertEquals(SignStatusEnum::DELETED, $sign->getStatus());
    }
}
