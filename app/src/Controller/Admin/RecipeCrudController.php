<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Enum\RecipeStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setFormOptions(
            [
                'validation_groups' => ['Default'],
            ]
        );
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield TextEditorField::new('description');
        yield IntegerField::new('servings');
        yield IntegerField::new('preparation_time');
        yield IntegerField::new('cooking_time');
        yield AssociationField::new('author', 'Auteur')
            ->setFormTypeOption('placeholder', 'Choisir un auteur');

        yield ChoiceField::new('status')
            ->setChoices(RecipeStatus::cases())->autocomplete();
    }
}
