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
        private ?LoggerInterface $logger = null
    ) {}

    /**
     * Envoie un email de confirmation au client après sa commande
     */
    public function sendOrderConfirmation($order): void
    {
        try {
            // Email de confirmation au client
            $emailClient = (new Email())
                ->from('contact@boucherie-eysa.fr')
                ->to($order->getCustomerEmail())
                ->subject('Commande confirmée #' . $order->getId() . ' - Boucherie Eysa')
                ->html($this->twig->render('emails/order_confirmation.html.twig', [
                    'order' => $order
                ]));
            $this->mailer->send($emailClient);

            // Email de notification à l'admin
            $emailAdmin = (new Email())
                ->from('contact@boucherie-eysa.fr')
                ->replyTo(method_exists($order, 'getCustomerEmail') ? (string) $order->getCustomerEmail() : 'contact@boucherie-eysa.fr')
                ->to('eysa.boucherie@gmail.com')
                ->subject('Nouvelle commande #' . $order->getId() . ' - À préparer')
                ->html($this->twig->render('emails/new_order_admin.html.twig', [
                    'order' => $order
                ]));
            $this->mailer->send($emailAdmin);
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
