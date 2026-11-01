<?php

namespace App\DataFixtures;

use App\Entity\Keywords;
use App\Service\CsvReader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Psr\Log\LoggerInterface;

class KeywordsFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%app.fixtures_csv_dir%')]
        private readonly string $fixturesDir,
        private readonly CsvReader $csvReader,
        private readonly LoggerInterface $logger,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $keywords = $this->csvReader->parse(
            $this->fixturesDir . '/keywords.csv'
        );

        $knownNames = [];

        foreach ($keywords as $data) {
            $reference = trim($data['reference'] ?? '');
            $name = trim($data['name'] ?? '');

            if ($reference === '' || $name === '') {
                $this->logger->warning('Mot-clé invalide', [
                    'data' => $data,
                ]);
                continue;
            }

            if ($this->hasReference($reference, Keywords::class)) {
                $this->logger->warning('Référence mot-clé dupliquée', [
                    'reference' => $reference,
                ]);
                continue;
            }

            $normalizedName = mb_strtolower($name);

            if (isset($knownNames[$normalizedName])) {
                $this->addReference(
                    $reference,
                    $knownNames[$normalizedName]
                );
                continue;
            }

            $keyword = new Keywords();
            $keyword->setName($name);

            $manager->persist($keyword);

            $this->addReference($reference, $keyword);
            $knownNames[$normalizedName] = $keyword;
        }

        $manager->flush();
    }
}
