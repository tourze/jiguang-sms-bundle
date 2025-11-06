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
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use JiguangSmsBundle\Entity\Sign;
use JiguangSmsBundle\Enum\SignStatusEnum;
use JiguangSmsBundle\Enum\SignTypeEnum;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;

/**
 * @extends AbstractCrudController<Sign>
 */
#[AdminCrud(
    routePath: '/jiguang-sms/sign',
    routeName: 'jiguang_sms_sign'
)]
final class SignCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sign::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('短信签名')
            ->setEntityLabelInPlural('短信签名管理')
            ->setPageTitle(Crud::PAGE_INDEX, '签名列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建签名')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑签名')
            ->setPageTitle(Crud::PAGE_DETAIL, '签名详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['sign', 'remark'])
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
        yield TextField::new('sign', '签名内容')
            ->setMaxLength(8)
            ->setRequired(true)
            ->setHelp('签名内容，最多8个字符')
        ;
        $typeField = EnumField::new('type', '签名类型');
        $typeField->setEnumCases(SignTypeEnum::cases());
        yield $typeField->setRequired(true);
        $statusField = EnumField::new('status', '审核状态');
        $statusField->setEnumCases(SignStatusEnum::cases());
        yield $statusField;
        yield IntegerField::new('signId', '极光签名ID')
            ->hideOnForm()
            ->setHelp('极光系统分配的签名ID')
        ;
        yield TextareaField::new('remark', '申请说明')
            ->setMaxLength(100)
            ->setNumOfRows(3)
            ->hideOnIndex()
        ;
        yield BooleanField::new('isDefault', '默认签名')
            ->setHelp('是否设为默认签名')
        ;
        yield BooleanField::new('useStatus', '使用状态')
            ->setHelp('当前是否在使用中')
        ;
        $image0Field = ImageField::new('image0', '资质证件1')
            ->setBasePath('uploads/jiguang-sms/signs')
            ->setUploadDir('public/uploads/jiguang-sms/signs')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->hideOnIndex()
        ;

        // 测试环境设置上传目录
        if ($this->getParameter('kernel.environment') === 'test') {
            $image0Field->setFormTypeOption('upload_dir', sys_get_temp_dir() . '/jiguang-sms-test-uploads/public/uploads/jiguang-sms/signs');
        }

        yield $image0Field;
        $image1Field = ImageField::new('image1', '资质证件2')
            ->setBasePath('uploads/jiguang-sms/signs')
            ->setUploadDir('public/uploads/jiguang-sms/signs')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->hideOnIndex()
        ;

        // 测试环境设置上传目录
        if ($this->getParameter('kernel.environment') === 'test') {
            $image1Field->setFormTypeOption('upload_dir', sys_get_temp_dir() . '/jiguang-sms-test-uploads/public/uploads/jiguang-sms/signs');
        }

        yield $image1Field;
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
            ->add(TextFilter::new('sign', '签名内容'))
            ->add(ChoiceFilter::new('type', '签名类型')->setChoices(
                array_combine(
                    array_map(fn ($s) => $s->getLabel(), SignTypeEnum::cases()),
                    array_map(fn ($s) => $s->value, SignTypeEnum::cases())
                )
            ))
            ->add(ChoiceFilter::new('status', '审核状态')->setChoices(
                array_combine(
                    array_map(fn ($s) => $s->getLabel(), SignStatusEnum::cases()),
                    array_map(fn ($s) => $s->value, SignStatusEnum::cases())
                )
            ))
            ->add(BooleanFilter::new('isDefault', '默认签名'))
            ->add(BooleanFilter::new('useStatus', '使用状态'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
