<?php

namespace App\Controller\Admin;

use App\Controller\Admin\RecipeIngredientCrudController;
use App\Entity\Recipe;
use App\Enum\RecipeStatus;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('recipe.title')
            ->setEntityLabelInPlural('recipe.title.plural')
            ->setFormOptions(
                [
                    'validation_groups' => ['Default'],
                ]
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'recipe.name');
        yield TextEditorField::new('description', 'recipe.description');
        yield IntegerField::new('servings', 'recipe.servings');
        yield IntegerField::new('preparation_time', 'recipe.preparation_time');
        yield IntegerField::new('cooking_time', 'recipe.cooking_time');
        yield AssociationField::new('author', 'recipe.author')
            ->setFormTypeOption('placeholder', 'recipe.choose_author');

        yield ChoiceField::new('status', 'recipe.status')
            ->setChoices(RecipeStatus::cases())->autocomplete();

        yield AssociationField::new('category', 'recipe.category')
            ->setFormTypeOption('placeholder', 'recipe.choose_category');

        yield CollectionField::new('recipeIngredients')
            ->setLabel('recipe.ingredients')
            ->setEntryToStringMethod('__toString')
            ->useEntryCrudForm(RecipeIngredientCrudController::class)
            ->allowAdd()
            ->allowDelete()
            ->renderExpanded()
            ->setEntryIsComplex()
            ->onlyOnForms();
    }
}
