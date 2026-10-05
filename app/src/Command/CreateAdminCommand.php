<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin if no admin exists',
    usages: ['admin_name <admin_name> email <email> firstname <firstname> lastname <lastname> password <password>']
)]
class CreateAdminCommand extends Command
{
    private UserRepository $user_repo;

    public function __construct(UserRepository $user_repo)
    {
        $this->user_repo = $user_repo;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the admin')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'The first name of the admin')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'The last name of the admin')
            ->setDescription('Creates a new admin if no admin exists')
            ->setHelp('This command allows you to create a new admin if no admin exists.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $admin = $this->user_repo->findAdmin();

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
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setDateAdd($dateImmutable);

        $this->user_repo->getEntityManager()->persist($user);
        $this->user_repo->getEntityManager()->flush();

        // création de l'admin ensuite
        return Command::SUCCESS;
    }
}
