<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('category.title')
            ->setEntityLabelInPlural('category.title.plural')
            ->setFormOptions(
                [
                    'validation_groups' => ['Default'],
                ]
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'category.name');
        yield TextEditorField::new('description', 'category.description');
    }
}
