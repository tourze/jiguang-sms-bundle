<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangSmsBundle\Entity\Message;

/**
 * @extends AbstractCrudController<Message>
 */
#[AdminCrud(
    routePath: '/jiguang-sms/message',
    routeName: 'jiguang_sms_message'
)]
final class MessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('短信记录')
            ->setEntityLabelInPlural('短信记录管理')
            ->setPageTitle(Crud::PAGE_INDEX, '短信列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建短信记录')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑短信记录')
            ->setPageTitle(Crud::PAGE_DETAIL, '短信详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['mobile', 'msgId', 'tag'])
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
            ->setHelp('接收短信的手机号码')
        ;
        yield AssociationField::new('template', '短信模板')
            ->setRequired(true)
            ->autocomplete()
        ;
        yield AssociationField::new('sign', '短信签名')
            ->autocomplete()
        ;
        yield TextField::new('msgId', '消息ID')
            ->setMaxLength(32)
            ->hideOnForm()
            ->setHelp('极光系统分配的消息ID')
        ;
        yield CodeEditorField::new('templateParams', '模板参数')
            ->setLanguage('javascript')
            ->setNumOfRows(4)
            ->hideOnIndex()
            ->setHelp('模板变量参数，JSON格式')
        ;
        yield TextField::new('tag', '标签')
            ->setMaxLength(50)
            ->hideOnIndex()
        ;
        yield IntegerField::new('status', '发送状态码')
            ->hideOnForm()
            ->setHelp('短信发送状态码')
        ;

        $isDelivered = BooleanField::new('delivered', '是否送达')
            ->hideOnForm()
        ;

        if (Crud::PAGE_INDEX === $pageName) {
            $isDelivered->setCustomOption('callable', fn (Message $entity) => $entity->isDelivered());
        }

        yield $isDelivered;

        yield DateTimeField::new('receiveTime', '状态接收时间')
            ->hideOnForm()
            ->setHelp('收到状态回调的时间')
        ;
        yield CodeEditorField::new('response', '发送结果')
            ->setLanguage('javascript')
            ->setNumOfRows(6)
            ->hideOnIndex()
            ->hideOnForm()
            ->setHelp('完整的发送结果响应')
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
            ->add(EntityFilter::new('template', '短信模板'))
            ->add(EntityFilter::new('sign', '短信签名'))
            ->add(TextFilter::new('msgId', '消息ID'))
            ->add(TextFilter::new('tag', '标签'))
            ->add(NumericFilter::new('status', '发送状态码'))
            ->add(BooleanFilter::new('delivered', '是否送达')
                ->setFormTypeOption('mapped', false)
            )
            ->add(DateTimeFilter::new('receiveTime', '状态接收时间'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
