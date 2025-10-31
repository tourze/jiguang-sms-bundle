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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangSmsBundle\Entity\Template;
use JiguangSmsBundle\Enum\TemplateStatusEnum;
use JiguangSmsBundle\Enum\TemplateTypeEnum;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;

/**
 * @extends AbstractCrudController<Template>
 */
#[AdminCrud(
    routePath: '/jiguang-sms/template',
    routeName: 'jiguang_sms_template'
)]
final class TemplateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Template::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('短信模板')
            ->setEntityLabelInPlural('短信模板管理')
            ->setPageTitle(Crud::PAGE_INDEX, '模板列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建模板')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑模板')
            ->setPageTitle(Crud::PAGE_DETAIL, '模板详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['template', 'remark'])
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
        yield TextareaField::new('template', '模板内容')
            ->setMaxLength(500)
            ->setNumOfRows(4)
            ->setRequired(true)
            ->setHelp('短信模板内容，支持变量占位符')
        ;
        $typeField = EnumField::new('type', '模板类型');
        $typeField->setEnumCases(TemplateTypeEnum::cases());
        yield $typeField->setRequired(true);
        $statusField = EnumField::new('status', '审核状态');
        $statusField->setEnumCases(TemplateStatusEnum::cases());
        yield $statusField;
        yield IntegerField::new('tempId', '极光模板ID')
            ->hideOnForm()
            ->setHelp('极光系统分配的模板ID')
        ;
        yield IntegerField::new('ttl', '验证码有效期')
            ->setHelp('验证码有效期，单位：秒')
            ->hideOnIndex()
        ;
        yield TextareaField::new('remark', '申请说明')
            ->setMaxLength(100)
            ->setNumOfRows(3)
            ->hideOnIndex()
        ;
        yield BooleanField::new('useStatus', '使用状态')
            ->setHelp('当前是否在使用中')
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
            ->add(TextFilter::new('template', '模板内容'))
            ->add(ChoiceFilter::new('type', '模板类型')->setChoices(
                array_combine(
                    array_map(fn ($s) => $s->getLabel(), TemplateTypeEnum::cases()),
                    array_map(fn ($s) => $s->value, TemplateTypeEnum::cases())
                )
            ))
            ->add(ChoiceFilter::new('status', '审核状态')->setChoices(
                array_combine(
                    array_map(fn ($s) => $s->getLabel(), TemplateStatusEnum::cases()),
                    array_map(fn ($s) => $s->value, TemplateStatusEnum::cases())
                )
            ))
            ->add(BooleanFilter::new('useStatus', '使用状态'))
            ->add(NumericFilter::new('ttl', '验证码有效期'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
