<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use JiguangSmsBundle\Entity\AccountBalance;

/**
 * @extends AbstractCrudController<AccountBalance>
 */
#[AdminCrud(
    routePath: '/jiguang-sms/account-balance',
    routeName: 'jiguang_sms_account_balance'
)]
final class AccountBalanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccountBalance::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('账号余量')
            ->setEntityLabelInPlural('账号余量管理')
            ->setPageTitle(Crud::PAGE_INDEX, '余量列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建余量记录')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑余量')
            ->setPageTitle(Crud::PAGE_DETAIL, '余量详情')
            ->setDefaultSort(['updateTime' => 'DESC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnIndex();
        yield AssociationField::new('account', '账号')
            ->setRequired(true)
            ->autocomplete()
        ;
        yield IntegerField::new('balance', '全类型短信余量')
            ->setHelp('短信余量总数')
            ->setTextAlign('right')
        ;
        yield IntegerField::new('voice', '语音短信余量')
            ->setHelp('语音验证码余量')
            ->setTextAlign('right')
        ;
        yield IntegerField::new('industry', '行业短信余量')
            ->setHelp('行业通知类短信余量')
            ->setTextAlign('right')
        ;
        yield IntegerField::new('market', '营销短信余量')
            ->setHelp('营销推广类短信余量')
            ->setTextAlign('right')
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
            ->add(EntityFilter::new('account', '账号'))
            ->add(NumericFilter::new('balance', '全类型短信余量'))
            ->add(NumericFilter::new('voice', '语音短信余量'))
            ->add(NumericFilter::new('industry', '行业短信余量'))
            ->add(NumericFilter::new('market', '营销短信余量'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
