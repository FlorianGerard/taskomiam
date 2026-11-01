<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Service\CsvReader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Psr\Log\LoggerInterface;

class IngredientFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%app.fixtures_csv_dir%')]
        private readonly string $fixturesDir,
        private readonly CsvReader $csvReader,
        private readonly LoggerInterface $logger,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $ingredients = $this->csvReader->parse(
            $this->fixturesDir . '/ingredients.csv'
        );

        $knownNames = [];

        foreach ($ingredients as $data) {
            $reference = trim($data['reference'] ?? '');
            $name = trim($data['name'] ?? '');

            if ($reference === '' || $name === '') {
                $this->logger->warning('Ingrédient invalide', [
                    'data' => $data,
                ]);
                continue;
            }

            if ($this->hasReference($reference, Ingredient::class)) {
                $this->logger->warning('Référence ingrédient dupliquée', [
                    'reference' => $reference,
                ]);
                continue;
            }

            $normalizedName = mb_strtolower($name);

            if (isset($knownNames[$normalizedName])) {
                // La nouvelle référence réutilise l'ingrédient existant.
                $this->addReference(
                    $reference,
                    $knownNames[$normalizedName]
                );
                continue;
            }

            $ingredient = new Ingredient();
            $ingredient->setName($name);

            $manager->persist($ingredient);

            $this->addReference($reference, $ingredient);
            $knownNames[$normalizedName] = $ingredient;
        }

        $manager->flush();
    }
}
