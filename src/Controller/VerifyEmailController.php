<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

/**
 * Controller pour la vérification des emails après inscription
 */
final class VerifyEmailController extends AbstractController
{
    /**
     * VÉRIFICATION D'EMAIL
     * 
     * Cette méthode est appelée quand l'utilisateur clique sur le lien
     * de vérification dans l'email qu'il a reçu après inscription.
     * 
     * Workflow :
     * 1. Je récupère le token depuis l'URL
     * 2. Je cherche l'utilisateur correspondant en base
     * 3. Je vérifie que le token est valide
     * 4. Je marque le compte comme vérifié
     * 5. J'affiche une page de confirmation
     * 
     * ROUTE : GET /verify-email/{token}
     */
    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(
        string $token,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        LoggerInterface $logger
    ): Response {
        // ÉTAPE 1 : Je cherche l'utilisateur avec ce token de vérification
        $user = $userRepository->findOneBy(['verificationToken' => $token]);
        
        // ÉTAPE 2 : Si le token est invalide ou expiré
        if (!$user) {
            $logger->warning('Tentative de vérification avec token invalide', ['token' => $token]);
            
            $this->addFlash('error', 'Le lien de vérification est invalide ou a expiré. Veuillez contacter le support.');
            return $this->redirectToRoute('app_login');
        }
        
        // ÉTAPE 3 : Si le compte est déjà vérifié
        if ($user->isVerified()) {
            $logger->info('Tentative de vérification d\'un compte déjà vérifié', [
                'email' => $user->getEmail()
            ]);
            
            $this->addFlash('info', 'Votre compte est déjà vérifié. Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }
        
        // ÉTAPE 4 : Je marque le compte comme vérifié
        try {
            $user->setIsVerified(true);
            $user->setVerificationToken(null); // Je supprime le token pour sécurité
            
            $em->flush();
            
            $logger->info('Email vérifié avec succès', ['email' => $user->getEmail()]);
            
            // ÉTAPE 5 : J'affiche la page de confirmation
            return $this->render('security/email_verified.html.twig', [
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            $logger->error('Erreur lors de la vérification email', [
                'email' => $user->getEmail(),
                'exception' => $e->getMessage()
            ]);
            
            $this->addFlash('error', 'Une erreur est survenue lors de la vérification. Veuillez réessayer.');
            return $this->redirectToRoute('app_login');
        }
    }
}
