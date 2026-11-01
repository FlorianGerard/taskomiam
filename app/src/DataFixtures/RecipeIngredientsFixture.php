<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use App\Enum\Unit;
use App\Service\CsvReader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Psr\Log\LoggerInterface;

class RecipeIngredientsFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        #[Autowire('%app.fixtures_csv_dir%')]
        private readonly string $fixturesDir,
        private readonly CsvReader $csvReader,
        private readonly LoggerInterface $logger,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $rows = $this->csvReader->parse(
            $this->fixturesDir . '/recipe_ingredients.csv'
        );

        foreach ($rows as $data) {
            $recipeRef = trim($data['recipe_ref'] ?? '');
            $ingredientRef = trim($data['ingredient_ref'] ?? '');
            $quantity = trim($data['quantity'] ?? '');
            $unitValue = trim($data['unit'] ?? '');

            if (!$this->hasReference($recipeRef, Recipe::class)) {
                $this->logger->warning('Recette introuvable', [
                    'reference' => $recipeRef,
                ]);
                continue;
            }

            if (!$this->hasReference($ingredientRef, Ingredient::class)) {
                $this->logger->warning('Ingrédient introuvable', [
                    'reference' => $ingredientRef,
                    'recipe' => $recipeRef,
                ]);
                continue;
            }

            if (
                !is_numeric($quantity) ||
                !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $quantity) ||
                (float) $quantity <= 0
            ) {
                $this->logger->warning('Quantité invalide', [
                    'recipe' => $recipeRef,
                    'ingredient' => $ingredientRef,
                    'quantity' => $quantity,
                ]);
                continue;
            }

            // Suppose que Unit est un enum backed par string.
            $unit = Unit::tryFrom($unitValue);

            if ($unit === null) {
                $this->logger->warning('Unité inconnue', [
                    'recipe' => $recipeRef,
                    'ingredient' => $ingredientRef,
                    'unit' => $unitValue,
                ]);
                continue;
            }

            $recipe = $this->getReference(
                $recipeRef,
                Recipe::class
            );

            $ingredient = $this->getReference(
                $ingredientRef,
                Ingredient::class
            );

            $recipeIngredient = new RecipeIngredient();

            $recipeIngredient
                ->setIngredient($ingredient)
                ->setQuantity($quantity)
                ->setUnit($unit);

            $recipe->addRecipeIngredient($recipeIngredient);

            $manager->persist($recipeIngredient);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RecipeFixtures::class,
            IngredientFixtures::class,
        ];
    }
}
