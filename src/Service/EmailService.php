<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Psr\Log\LoggerInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private ?LoggerInterface $logger = null,
        private ?string $fromAddress = null
    ) {}

    /**
     * Envoie un email de confirmation au client après sa commande
     */
    public function sendOrderConfirmation($order): void
    {
        try {
            if (!method_exists($order, 'getCustomerEmail') || !$order->getCustomerEmail()) {
                $this->logger?->warning('Email client manquant, envoi annulé', [
                    'orderId' => method_exists($order, 'getId') ? $order->getId() : null,
                ]);
                // On envoie quand même l'admin pour suivi
            }
            $from = $this->fromAddress ?: 'contact@boucherie-eysa.fr';
            $bccMonitoring = $_ENV['MAILER_BCC'] ?? $_SERVER['MAILER_BCC'] ?? null; // Pour copie technique si configuré

            // Email de confirmation au client
            if ($order->getCustomerEmail()) {
                $emailClient = (new Email())
                    ->from($from)
                    ->to($order->getCustomerEmail())
                    ->subject('Commande confirmée #' . $order->getId() . ' - Boucherie Eysa')
                    ->returnPath($from)
                    ->text(sprintf(
                        'Bonjour %s, votre commande #%d a été confirmée. Total: %0.2f €.',
                        (string) $order->getCustomerName(),
                        (int) $order->getId(),
                        (float) ($order->getTotalTtcCents() / 100)
                    ))
                    ->html($this->twig->render('emails/order_confirmation.html.twig', [
                        'order' => $order
                    ]));
                if ($bccMonitoring) {
                    $emailClient->bcc($bccMonitoring);
                }
                $this->mailer->send($emailClient);
                $this->logger?->info('Email confirmation client envoyé', [
                    'orderId' => $order->getId(),
                    'to' => $order->getCustomerEmail()
                ]);
            }

            // Email de notification à l'admin
            $emailAdmin = (new Email())
                ->from($from)
                ->to('eysa.boucherie@gmail.com')
                ->subject('Nouvelle commande #' . $order->getId() . ' - À préparer')
                ->returnPath($from)
                ->replyTo($order->getCustomerEmail() ?: $from)
                ->text(sprintf(
                    'Nouvelle commande #%d - Client: %s - Total: %0.2f €.',
                    (int) $order->getId(),
                    (string) $order->getCustomerName(),
                    (float) ($order->getTotalTtcCents() / 100)
                ))
                ->html($this->twig->render('emails/new_order_admin.html.twig', [
                    'order' => $order
                ]));
            if ($bccMonitoring) {
                $emailAdmin->bcc($bccMonitoring);
            }
            $this->mailer->send($emailAdmin);
            $this->logger?->info('Email admin commande envoyé', [
                'orderId' => $order->getId(),
                'adminTo' => 'eysa.boucherie@gmail.com'
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur email, on n'interrompt pas le processus de commande
            if ($this->logger) {
                $this->logger->error('Erreur envoi emails commande', [
                    'orderId' => method_exists($order, 'getId') ? $order->getId() : null,
                    'exception' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Envoie une notification de nouvelle commande aux admins
     */
    public function notifyNewOrderToAdmins($order): void
    {
        try {
            // Récupérer tous les admins ou utiliser l'email fixe pour l'instant
            $adminEmail = 'eysa.boucherie@gmail.com';

            $email = (new Email())
                ->from('contact@boucherie-eysa.fr')
                ->to($adminEmail)
                ->subject('Nouvelle commande #' . $order->getId() . ' - À préparer')
                ->html($this->twig->render('emails/new_order_admin.html.twig', [
                    'order' => $order
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Erreur notification admin commande', [
                    'orderId' => method_exists($order, 'getId') ? $order->getId() : null,
                    'exception' => $e->getMessage(),
                ]);
            }
        }
    }
}
