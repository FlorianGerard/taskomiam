<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\CsvReader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%app.fixtures_csv_dir%')]
        private readonly string $fixturesDir,
        #[Autowire('%env(FIXTURE_USER_PASSWORD)%')]
        private readonly string $fixturePassword,
        private readonly CsvReader $csvReader,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly LoggerInterface $logger,
    ) {}

    public function load(ObjectManager $manager): void
    {
        if ($this->fixturePassword === '') {
            throw new \RuntimeException(
                'FIXTURE_USER_PASSWORD ne peut pas être vide.'
            );
        }

        $users = $this->csvReader->parse(
            $this->fixturesDir . '/users.csv'
        );

        $knownEmails = [];

        foreach ($users as $data) {
            $reference = trim($data['reference'] ?? '');
            $email = trim($data['email'] ?? '');
            $firstname = trim($data['firstname'] ?? '');
            $lastname = trim($data['lastname'] ?? '');

            if (
                $reference === '' ||
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                $firstname === '' ||
                $lastname === ''
            ) {
                $this->logger->warning('Utilisateur invalide', [
                    'data' => $data,
                ]);
                continue;
            }

            if ($this->hasReference($reference, User::class)) {
                $this->logger->warning('Référence utilisateur dupliquée', [
                    'reference' => $reference,
                ]);
                continue;
            }

            $normalizedEmail = mb_strtolower($email);

            if (isset($knownEmails[$normalizedEmail])) {
                $this->addReference(
                    $reference,
                    $knownEmails[$normalizedEmail]
                );
                continue;
            }

            $roles = array_filter(
                array_map(
                    'trim',
                    explode('|', $data['roles'] ?? 'ROLE_USER')
                )
            );

            $user = new User();

            $user
                ->setEmail($email)
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setRoles(array_values($roles))
                ->setDateAdd(new \DateTimeImmutable());

            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $this->fixturePassword
                )
            );

            $manager->persist($user);

            $this->addReference($reference, $user);
            $knownEmails[$normalizedEmail] = $user;
        }

        $manager->flush();
    }
}
