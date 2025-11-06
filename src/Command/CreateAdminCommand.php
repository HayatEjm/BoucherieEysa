<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un compte administrateur',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Vérifier si l'admin existe déjà
        $existingAdmin = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => 'eysa.boucherie@gmail.com']);

        if ($existingAdmin) {
            $io->warning('Le compte administrateur existe déjà !');
            return Command::SUCCESS;
        }

        // Créer le nouveau compte admin
        $admin = new User();
        $admin->setEmail('eysa.boucherie@gmail.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Boucherie Eysa');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        
        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'Chateauroux.36');
        $admin->setPassword($hashedPassword);

        // Sauvegarder
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('Compte administrateur créé avec succès !');
        $io->info('Email: eysa.boucherie@gmail.com');
        $io->info('Mot de passe: Chateauroux.36');

        return Command::SUCCESS;
    }
}
