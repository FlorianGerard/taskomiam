<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('admin/form/ingredient_suggestions.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom')
            ->setFormTypeOption(
                'attr.data-ingredient-search',
                'true'
            );
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        if ($entityInstance instanceof Ingredient && !$entityInstance->getRecipeIngredients()->isEmpty()) {
            $this->addFlash(
                'danger',
                'Impossible de supprimer cet ingrédient. Il est utilisé dans une ou plusieurs recettes.'
            );

            return;
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }


    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addJsFile(
                Asset::new('js/admin/ingredient-suggestions.js')
                    ->defer()
                    ->onlyOnForms()
            );
    }
}
