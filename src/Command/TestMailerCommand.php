<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:test-mail',
    description: 'Envoie un email de test pour valider la configuration SMTP',
)]
class TestMailerCommand extends Command
{
    public function __construct(private MailerInterface $mailer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('to', InputArgument::OPTIONAL, 'Destinataire du mail de test', 'eysa.boucherie@gmail.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $to = (string) $input->getArgument('to');

        $io->title('Test d\'envoi SMTP');
        $io->text('Envoi vers: ' . $to);

        try {
            $email = (new Email())
                ->from('contact@boucherie-eysa.fr')
                ->to($to)
                ->subject('Test SMTP - Boucherie Eysa (' . (getenv('APP_ENV') ?: 'dev') . ')')
                ->text('Ceci est un email de test envoyé via Symfony Mailer.');

            $this->mailer->send($email);
            $io->success('Email de test envoyé avec succès.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Échec de l\'envoi: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
