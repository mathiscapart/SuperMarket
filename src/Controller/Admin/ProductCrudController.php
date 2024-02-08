<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $category = new Category();
        yield TextField::new('name');
        yield ImageField::new('picture')
            ->setUploadedFileNamePattern("[uuid].[extension]")
            ->setBasePath('products/')
            ->setUploadDir("public/products");
        yield IntegerField::new('price');
        yield IntegerField::new('stock');
        $createdAt = DateTimeField::new('created_at');
        if (Crud::PAGE_EDIT === $pageName) {
                        yield $createdAt->setFormTypeOption('disabled', true);
                    } else {
                        yield $createdAt;
                    }
        yield DateTimeField::new('updated_at');
        yield TextEditorField::new('description');
        yield BooleanField::new('visible');
        yield AssociationField::new('category');
    }

}
