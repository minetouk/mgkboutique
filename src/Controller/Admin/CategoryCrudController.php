<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Entity\Category;

class CategoryCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string
  {
    return Category::class;
  }

  // public function configureFields(string $pageName): iterable
  // {
  //   return [
  //     IdField::new('id'),
  //     TextField::new('name'),
  //     TextEditorField::new('description'),
  //   ];
  // }
}

