<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use App\Entity\User;

/**
 * Vérifie que l'utilisateur a validé son email avant de le laisser se connecter
 */
class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        
        // Vérifier que c'est bien un User de notre app
        if (!$user instanceof User) {
            return;
        }
        
        // Si le compte n'est pas vérifié, on bloque la connexion
        if (!$user->isVerified()) {
            // On annule l'authentification
            $event->getRequest()->getSession()->invalidate();
            
            // Message flash
            $event->getRequest()->getSession()->getFlashBag()->add(
                'error',
                'Votre compte n\'est pas encore vérifié. Veuillez vérifier votre email et cliquer sur le lien de vérification.'
            );
            
            // Redirection vers login
            $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
            $event->setResponse($response);
        }
    }
}
