<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\UserPassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin if no admin exists',
    usages: [
        'app:create-admin',
        'app:create-admin admin@example.com John Doe',
    ]
)]
class CreateAdminCommand extends Command
{

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPassword $userPassword,
        private ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the admin')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'The first name of the admin')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'The last name of the admin')
            ->setHelp('This command allows you to create a new admin if no admin exists.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $admin = $this->userRepository->findAdmin();

        if ($admin) {
            $io->warning('An admin already exists.');
            return Command::FAILURE;
        }

        $email = $input->getArgument('email')
            ?? $io->ask('Email');

        $firstname = $input->getArgument('firstname')
            ?? $io->ask('First name');

        $lastname = $input->getArgument('lastname')
            ?? $io->ask('Last name');

        $password = $io->askHidden('Password');

        if (!$email || !$firstname || !$lastname || !$password) {
            $io->error('All fields are required.');
            return Command::FAILURE;
        }
        $dateImmutable = new \DateTimeImmutable();

        $user = new \App\Entity\User();
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setPlainPassword($password);
        $errors = $this->validator->validate(
            $user,
            null,
            ['Default', 'user_create']
        );

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $io->error($error->getMessage());
            }

            return Command::FAILURE;
        }

        $this->userPassword->hashPassword($user);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setDateAdd($dateImmutable);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $io->error('Failed to create the admin: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success(sprintf(
            'Admin "%s" has been created successfully.',
            $email
        ));

        // création de l'admin ensuite
        return Command::SUCCESS;
    }
}
