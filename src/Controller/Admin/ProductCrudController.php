<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;






class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return[
             TextField::new('name'),
             SlugField::new('slug')->setTargetFieldName('name'),
             TextField::new('subtitle'),
             ImageField::new(propertyName: 'illustration') 
                -> setBasePath('uploads') 
                -> setUploadDir('public/uploads/')
                -> setUploadedFileNamePattern('[randomhash].[extension]')
                -> setRequired(false),
             TextareaField::new('description'),
             BooleanField::new('isBest'),
             MoneyField::new('price') -> setCurrency('EUR'),
             AssociationField::new('category')
        ];

    }

}
