<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangSmsBundle\Entity\VoiceCode;

/**
 * @extends AbstractCrudController<VoiceCode>
 */
#[AdminCrud(
    routePath: '/jiguang-sms/voice-code',
    routeName: 'jiguang_sms_voice_code'
)]
final class VoiceCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VoiceCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('语音验证码')
            ->setEntityLabelInPlural('语音验证码管理')
            ->setPageTitle(Crud::PAGE_INDEX, '语音验证码列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建语音验证码记录')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑语音验证码记录')
            ->setPageTitle(Crud::PAGE_DETAIL, '语音验证码详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['mobile', 'code', 'msgId'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnIndex();
        yield AssociationField::new('account', '所属账号')
            ->setRequired(true)
            ->autocomplete()
        ;
        yield TextField::new('mobile', '手机号')
            ->setMaxLength(20)
            ->setRequired(true)
            ->setHelp('接收语音验证码的手机号码')
        ;
        yield TextField::new('code', '验证码')
            ->setMaxLength(6)
            ->setRequired(true)
            ->setHelp('6位数字验证码')
        ;
        yield IntegerField::new('ttl', '有效期')
            ->setHelp('验证码有效期，单位：秒')
            ->setTextAlign('right')
        ;
        yield TextField::new('msgId', '消息ID')
            ->setMaxLength(32)
            ->hideOnForm()
            ->setHelp('极光系统分配的消息ID')
        ;
        yield BooleanField::new('verified', '是否已验证')
            ->setHelp('验证码是否已被验证')
        ;
        yield IntegerField::new('status', '发送状态码')
            ->hideOnForm()
            ->setHelp('语音发送状态码')
        ;

        $isDelivered = BooleanField::new('delivered', '是否送达')
            ->hideOnForm()
        ;

        if (Crud::PAGE_INDEX === $pageName) {
            $isDelivered->setCustomOption('callable', fn (VoiceCode $entity) => $entity->isDelivered());
        }

        yield $isDelivered;

        yield DateTimeField::new('verifyTime', '验证时间')
            ->hideOnForm()
            ->setHelp('验证码被验证的时间')
        ;
        yield DateTimeField::new('receiveTime', '状态接收时间')
            ->hideOnForm()
            ->setHelp('收到状态回调的时间')
        ;
        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
        ;
        yield DateTimeField::new('updateTime', '更新时间')
            ->onlyOnDetail()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('account', '所属账号'))
            ->add(TextFilter::new('mobile', '手机号'))
            ->add(TextFilter::new('code', '验证码'))
            ->add(TextFilter::new('msgId', '消息ID'))
            ->add(BooleanFilter::new('verified', '是否已验证'))
            ->add(NumericFilter::new('status', '发送状态码'))
            ->add(BooleanFilter::new('delivered', '是否送达')
                ->setFormTypeOption('mapped', false)
            )
            ->add(NumericFilter::new('ttl', '有效期'))
            ->add(DateTimeFilter::new('verifyTime', '验证时间'))
            ->add(DateTimeFilter::new('receiveTime', '状态接收时间'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
