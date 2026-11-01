<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\CsvReader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Psr\Log\LoggerInterface;

class CategoryFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%app.fixtures_csv_dir%')]
        private readonly string $fixturesDir,
        private readonly CsvReader $csvReader,
        private readonly LoggerInterface $logger,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $categories = $this->csvReader->parse(
            $this->fixturesDir . '/categories.csv'
        );

        foreach ($categories as $data) {
            $reference = trim($data['reference'] ?? '');
            $name = trim($data['name'] ?? '');

            if ($reference === '' || $name === '') {
                $this->logger->warning('Catégorie invalide', [
                    'data' => $data,
                ]);
                continue;
            }

            if ($this->hasReference($reference, Category::class)) {
                $this->logger->warning('Référence catégorie dupliquée', [
                    'reference' => $reference,
                ]);
                continue;
            }

            $category = new Category();
            $category->setName($name);
            $category->setDescription(
                trim($data['description'] ?? '')
            );

            $manager->persist($category);
            $this->addReference($reference, $category);
        }

        $manager->flush();
    }
}
