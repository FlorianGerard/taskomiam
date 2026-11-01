<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Keywords;
use App\Entity\Recipe;
use App\Entity\User;
use App\Service\CsvReader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Psr\Log\LoggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        #[Autowire('%app.fixtures_csv_dir%')]
        private readonly string $fixturesDir,
        private readonly CsvReader $csvReader,
        private readonly LoggerInterface $logger,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $recipes = $this->csvReader->parse(
            $this->fixturesDir . '/recipes.csv'
        );

        foreach ($recipes as $data) {
            $reference = trim($data['reference'] ?? '');
            $name = trim($data['name'] ?? '');
            $authorRef = trim($data['author_ref'] ?? '');

            if (
                $reference === '' ||
                mb_strlen($name) < 5 ||
                mb_strlen($name) > 255
            ) {
                $this->logger->warning('Recette invalide', [
                    'data' => $data,
                ]);
                continue;
            }

            if ($this->hasReference($reference, Recipe::class)) {
                $this->logger->warning('Référence recette dupliquée', [
                    'reference' => $reference,
                ]);
                continue;
            }

            if (!$this->hasReference($authorRef, User::class)) {
                $this->logger->warning('Auteur introuvable', [
                    'recipe' => $reference,
                    'author' => $authorRef,
                ]);
                continue;
            }

            $servings = filter_var(
                $data['servings'] ?? null,
                FILTER_VALIDATE_INT
            );
            $preparationTime = filter_var(
                $data['preparation_time'] ?? null,
                FILTER_VALIDATE_INT
            );
            $cookingTime = filter_var(
                $data['cooking_time'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (
                $servings === false || $servings <= 0 ||
                $preparationTime === false || $preparationTime < 0 ||
                $cookingTime === false || $cookingTime < 0
            ) {
                $this->logger->warning('Valeurs numériques invalides', [
                    'recipe' => $reference,
                ]);
                continue;
            }

            $recipe = new Recipe();

            $recipe
                ->setName($name)
                ->setAuthor(
                    $this->getReference($authorRef, User::class)
                )
                ->setServings($servings)
                ->setDescription(
                    trim($data['description'] ?? '') ?: null
                )
                ->setPreparationTime($preparationTime)
                ->setCookingTime($cookingTime);

            foreach ($this->getCategoriesFromCsv($data) as $category) {
                $recipe->addCategory($category);
            }

            foreach ($this->getKeywordsFromCsv($data) as $keyword) {
                $recipe->addKeyword($keyword);
            }

            $manager->persist($recipe);
            $this->addReference($reference, $recipe);
        }

        $manager->flush();
    }

    private function getCategoriesFromCsv(array $data): array
    {
        $references = array_filter(
            array_map(
                'trim',
                explode('|', $data['categories'] ?? '')
            )
        );

        $categories = [];

        foreach ($references as $reference) {
            if (!$this->hasReference($reference, Category::class)) {
                $this->logger->warning('Catégorie introuvable', [
                    'reference' => $reference,
                    'recipe' => $data['reference'] ?? null,
                ]);
                continue;
            }

            $categories[] = $this->getReference(
                $reference,
                Category::class
            );
        }

        return $categories;
    }

    private function getKeywordsFromCsv(array $data): array
    {
        $references = array_filter(
            array_map(
                'trim',
                explode('|', $data['recipe_keywords'] ?? '')
            )
        );

        $keywords = [];

        foreach ($references as $reference) {
            if (!$this->hasReference($reference, Keywords::class)) {
                $this->logger->warning('Mot-clé introuvable', [
                    'reference' => $reference,
                    'recipe' => $data['reference'] ?? null,
                ]);
                continue;
            }

            $keywords[] = $this->getReference(
                $reference,
                Keywords::class
            );
        }

        return $keywords;
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            KeywordsFixtures::class,
            UserFixtures::class,
        ];
    }
}
