<?php

declare(strict_types=1);

namespace JiguangSmsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangSmsBundle\Entity\Account;

/**
 * @extends AbstractCrudController<Account>
 */
#[AdminCrud(
    routePath: '/jiguang-sms/account',
    routeName: 'jiguang_sms_account'
)]
final class AccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Account::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('极光短信账号')
            ->setEntityLabelInPlural('极光短信账号管理')
            ->setPageTitle(Crud::PAGE_INDEX, '账号列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建账号')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑账号')
            ->setPageTitle(Crud::PAGE_DETAIL, '账号详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['title', 'appKey'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnIndex();
        yield TextField::new('title', '标题')
            ->setMaxLength(100)
            ->setRequired(true)
        ;
        yield TextField::new('appKey', 'AppKey')
            ->setMaxLength(64)
            ->setRequired(true)
            ->setHelp('极光短信应用的AppKey')
        ;
        yield TextField::new('masterSecret', 'MasterSecret')
            ->setMaxLength(128)
            ->setRequired(true)
            ->setHelp('极光短信应用的MasterSecret')
            ->hideOnIndex()
        ;
        yield BooleanField::new('valid', '有效')
            ->setHelp('标记账号是否有效可用')
        ;
        yield TextField::new('createdBy', '创建者')
            ->hideOnForm()
        ;
        yield TextField::new('updatedBy', '更新者')
            ->hideOnForm()
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
            ->add(TextFilter::new('title', '标题'))
            ->add(TextFilter::new('appKey', 'AppKey'))
            ->add(BooleanFilter::new('valid', '有效'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
