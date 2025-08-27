<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller pour la sécurité (connexion/déconnexion)
 * 
 * Ici je gère tout ce qui concerne l'authentification :
 * - Affichage du formulaire de connexion
 * - Traitement des erreurs de connexion  
 * - Déconnexion (gérée automatiquement par Symfony)
 */
class SecurityController extends AbstractController
{
    /**
     * PAGE DE CONNEXION
     * 
     * Ce que fait cette méthode :
     * 1. Je récupère les erreurs de connexion s'il y en a
     * 2. Je récupère le dernier nom d'utilisateur saisi
     * 3. J'affiche le formulaire avec ces informations
     * 
     * ROUTE : GET/POST /login
     * NOTE : Le traitement POST est géré automatiquement par Symfony Security
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, je le redirige vers son compte
        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        // Je récupère l'erreur de connexion s'il y en a une
        // Exemple : "Invalid credentials" si mauvais mot de passe
        $error = $authenticationUtils->getLastAuthenticationError();

        // Je récupère le dernier nom d'utilisateur saisi pour le pré-remplir
        // Pratique si l'utilisateur s'est trompé dans son mot de passe
        $lastUsername = $authenticationUtils->getLastUsername();

        // J'envoie tout ça au template pour affichage
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * DÉCONNEXION
     * 
     * Cette méthode ne fait rien car Symfony s'occupe de tout automatiquement :
     * 1. Il supprime la session utilisateur
     * 2. Il redirige vers la page d'accueil (ou celle configurée)
     * 
     * ROUTE : GET /logout
     * NOTE : Le traitement réel est dans config/packages/security.yaml
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être vide - elle est interceptée par le système
        // de sécurité Symfony qui s'occupe de la déconnexion automatiquement
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
