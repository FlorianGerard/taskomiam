<?php

namespace App\Controller\Admin;

use App\Entity\RecipeIngredient;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use App\Enum\Unit;

class RecipeIngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeIngredient::class;
    }


    public function configureFields(string $pageName): iterable
    {

        yield AssociationField::new('ingredient', 'Ingrédient')
            ->autocomplete();
        yield NumberField::new('quantity');
        yield ChoiceField::new('unit')
            ->setChoices(Unit::cases())->autocomplete();
    }
}
