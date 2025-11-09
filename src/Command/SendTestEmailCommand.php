<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:mail:test',
    description: 'Envoie un email de test via le Mailer Symfony (synchrone).'
)]
class SendTestEmailCommand extends Command
{
    public function __construct(private readonly MailerInterface $mailer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('to', InputArgument::REQUIRED, 'Adresse email du destinataire')
            ->addOption('subject', 's', InputOption::VALUE_REQUIRED, 'Sujet du message', 'Test Boucherie Eysa')
            ->addOption('from', 'f', InputOption::VALUE_REQUIRED, 'Adresse expéditeur (optionnelle)')
            ->addOption('text', 't', InputOption::VALUE_OPTIONAL, 'Contenu texte', 'Ceci est un email de test envoyé en mode synchrone.')
            ->addOption('html', null, InputOption::VALUE_OPTIONAL, 'Contenu HTML (facultatif)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $to = (string) $input->getArgument('to');
        $subject = (string) $input->getOption('subject');
        $from = $input->getOption('from');
        $text = (string) $input->getOption('text');
        $html = $input->getOption('html');

        $email = (new Email())
            ->to($to)
            ->subject($subject)
            ->text($text);
        // Fallback automatique si aucun expéditeur n'est fourni: variable d'env MAILER_FROM ou adresse par défaut.
        if (empty($from)) {
            $envFrom = $_ENV['MAILER_FROM'] ?? $_SERVER['MAILER_FROM'] ?? null;
            $from = $envFrom ?: 'contact@boucherie-eysa.fr';
        }
        $email->from((string) $from);

        if (!empty($html)) {
            $email->html((string) $html);
        }

        try {
            $this->mailer->send($email);
            $output->writeln('<info>Email de test envoyé avec succès à ' . $to . '.</info>');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln('<error>Echec de l\'envoi: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
