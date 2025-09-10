<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig
    ) {}

    /**
     * Envoie un email de confirmation au client après sa commande
     */
    public function sendOrderConfirmation($order): void
    {
        try {
            $email = (new Email())
                ->from('eysa.boucherie@gmail.com')
                ->to($order->getCustomerEmail())
                ->subject('✅ Commande confirmée #' . $order->getId() . ' - Boucherie Eysa')
                ->html($this->twig->render('emails/order_confirmation.html.twig', [
                    'order' => $order
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            // En cas d'erreur email, on n'interrompt pas le processus de commande
            // En production, on loggerait cette erreur
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
                ->from('eysa.boucherie@gmail.com')
                ->to($adminEmail)
                ->subject('🛒 Nouvelle commande #' . $order->getId() . ' - À préparer')
                ->html($this->twig->render('emails/new_order_admin.html.twig', [
                    'order' => $order
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            // En cas d'erreur, on n'interrompt pas le processus
        }
    }
}
